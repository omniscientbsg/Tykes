<?php
/**
 * Tykes Lead Management
 * Saves form submissions to WordPress + forwards to Kylas CRM
 */
if (!defined('ABSPATH'))
    exit;

// ─── Register Custom Post Type ───
function tykes_register_leads_cpt()
{
    register_post_type('tykes_lead', array(
        'labels' => array(
            'name' => 'Franchise Leads',
            'singular_name' => 'Franchise Lead',
            'menu_name' => 'Franchise Leads',
            'all_items' => 'All Leads',
            'add_new_item' => 'Add New Lead',
            'edit_item' => 'View Lead',
            'search_items' => 'Search Leads',
            'not_found' => 'No leads found',
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-groups',
        'menu_position' => 25,
        'supports' => array('title'),
        'capability_type' => 'post',
    ));
}
add_action('init', 'tykes_register_leads_cpt');

// ─── Meta Box ───
function tykes_lead_meta_boxes()
{
    add_meta_box('tykes_lead_details', 'Lead Details', 'tykes_lead_meta_box_html', 'tykes_lead', 'normal', 'high');
}
add_action('add_meta_boxes', 'tykes_lead_meta_boxes');

function tykes_lead_meta_box_html($post)
{
    $fields = array(
        'phone' => 'Phone',
        'email' => 'Email',
        'city' => 'City',
        'investment_range' => 'Investment Range',
        'timeframe' => 'Timeframe',
        'form_source' => 'Form Source',
        'kylas_status' => 'Kylas Status',
        'submitted_at' => 'Submitted At',
    );
    echo '<table class="form-table"><tbody>';
    foreach ($fields as $key => $label) {
        $v = get_post_meta($post->ID, '_tykes_' . $key, true);
        echo '<tr><th style="width:180px"><strong>' . esc_html($label) . '</strong></th>';
        echo '<td><input type="text" value="' . esc_attr($v) . '" class="regular-text" readonly></td></tr>';
    }
    echo '</tbody></table>';
}

// ─── Admin List Columns ───
function tykes_lead_columns($columns)
{
    return array(
        'cb' => $columns['cb'],
        'title' => 'Name',
        'phone' => 'Phone',
        'email' => 'Email',
        'city' => 'City',
        'investment' => 'Investment',
        'form_source' => 'Source',
        'kylas' => 'Kylas',
        'date' => 'Date',
    );
}
add_filter('manage_tykes_lead_posts_columns', 'tykes_lead_columns');

function tykes_lead_column_data($column, $post_id)
{
    $map = array(
        'phone' => '_tykes_phone',
        'email' => '_tykes_email',
        'city' => '_tykes_city',
        'investment' => '_tykes_investment_range',
        'form_source' => '_tykes_form_source',
        'kylas' => '_tykes_kylas_status',
    );
    if (isset($map[$column]))
        echo esc_html(get_post_meta($post_id, $map[$column], true));
}
add_action('manage_tykes_lead_posts_custom_column', 'tykes_lead_column_data', 10, 2);

// ─── Debug Admin Page ───
function tykes_debug_admin_menu()
{
    add_submenu_page(
        'edit.php?post_type=tykes_lead',
        'Kylas Debug',
        'Kylas Debug',
        'manage_options',
        'tykes-kylas-debug',
        'tykes_debug_admin_page'
    );
}
add_action('admin_menu', 'tykes_debug_admin_menu');

function tykes_debug_admin_page()
{
    $api_key = '33a8157b-b808-423a-a383-34e7a2529b73:5901';
    $style_box = 'background:#1e1e1e;color:#d4d4d4;padding:16px;border-radius:6px;font-family:monospace;font-size:12px;white-space:pre-wrap;word-break:break-all;margin-bottom:24px;max-height:500px;overflow-y:auto;';

    echo '<div class="wrap"><h1>Kylas Debug</h1>';

    // ── Manual format tester ──
    // Tries every possible customFieldValues format against the real Kylas API
    if (isset($_GET['test_formats'])) {
        $phone = '9999999990';
        $email = 'formattest_' . time() . '@tykes.school';

        // All the formats we want to try for field 2358097, option 2842322
        $formats = array(
            'plain_int' => array('2358097' => 2842322),
            'id_wrapped' => array('2358097' => array('id' => 2842322)),
            'id_str_wrapped' => array('2358097' => array('id' => '2842322')),
            'name_wrapped' => array('2358097' => array('name' => 'Within 3 Months')),
            'value_wrapped' => array('2358097' => array('value' => 2842322)),
            'both_fields_int' => array('2358096' => 2842319, '2358097' => 2842322),
            'both_id_wrapped' => array('2358096' => array('id' => 2842319), '2358097' => array('id' => 2842322)),
        );

        echo '<h2>🧪 Format Test Results</h2>';
        echo '<p>Sending a test lead with each possible <code>customFieldValues</code> format. Green = HTTP 200/201. Red = error.</p>';
        echo '<table class="widefat striped"><thead><tr><th>Format</th><th>customFieldValues sent</th><th>HTTP</th><th>Result</th></tr></thead><tbody>';

        foreach ($formats as $format_name => $cfv_array) {
            // Build cfv as object
            $cfv = new stdClass();
            foreach ($cfv_array as $k => $v) {
                $key = (string) $k;
                $cfv->$key = $v;
            }

            $payload = array(
                'firstName' => 'FormatTest',
                'lastName' => $format_name,
                'phoneNumbers' => array(array('type' => 'MOBILE', 'code' => 'IN', 'value' => $phone, 'dialCode' => '+91', 'primary' => true)),
                'emails' => array(array('type' => 'OFFICE', 'value' => $email, 'primary' => true)),
                'customFieldValues' => $cfv,
            );

            $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $byte_length = mb_strlen($json, '8bit');

            $ch = curl_init('https://api.kylas.io/v1/leads/');
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'api-key: ' . $api_key,
                    'Content-Length: ' . $byte_length,
                ),
                CURLOPT_TIMEOUT => 15,
                CURLOPT_SSL_VERIFYPEER => true,
            ));

            $resp_body = curl_exec($ch);
            $http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $decoded = json_decode($resp_body, true);
            $is_ok = ($http_code >= 200 && $http_code < 300);
            $color = $is_ok ? 'green' : '#cc3333';
            $result = $is_ok
                ? '✅ SUCCESS — lead ID: ' . ($decoded['id'] ?? '?')
                : '❌ ' . ($decoded['message'] ?? $resp_body);

            // Show error details if any
            if (!empty($decoded['errorDetails'])) {
                foreach ($decoded['errorDetails'] as $ed) {
                    $result .= ' | field ' . ($ed['field'] ?? '?') . ': ' . ($ed['message'] ?? '?');
                }
            }

            $cfv_display = json_encode($cfv_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            echo '<tr>';
            echo '<td><strong>' . esc_html($format_name) . '</strong></td>';
            echo '<td><pre style="margin:0;font-size:11px">' . esc_html($cfv_display) . '</pre></td>';
            echo '<td style="color:' . $color . ';font-weight:bold">' . esc_html($http_code) . '</td>';
            echo '<td style="color:' . $color . '">' . esc_html($result) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
        echo '<p style="color:#666;margin-top:8px">Note: Test leads created above can be deleted from Kylas.</p>';
    }

    $test_url = add_query_arg('test_formats', '1');
    echo '<a href="' . esc_url($test_url) . '" class="button button-primary" style="margin-bottom:12px">🧪 Run Format Test (creates test leads in Kylas)</a> ';

    // ── Fetch Fields from Kylas API ──
    $fields_url = add_query_arg('fetch_fields', '1');
    echo '<a href="' . esc_url($fields_url) . '" class="button button-secondary" style="margin-bottom:12px">📋 Fetch Lead Fields from Kylas API</a> ';

    // ── Basic lead test (no custom fields) ──
    $basic_url = add_query_arg('test_basic', '1');
    echo '<a href="' . esc_url($basic_url) . '" class="button button-secondary" style="margin-bottom:12px">✅ Test Basic Lead (no custom fields)</a>';

    // ── Show fetched fields ──
    if (isset($_GET['fetch_fields'])) {
        echo '<hr style="margin:24px 0"><h2>📋 Lead Fields from Kylas API</h2>';

        // Try multiple possible endpoints
        $endpoints = array(
            'https://api.kylas.io/v1/leads/fields',
            'https://api.kylas.io/v1/fields/leads',
        );

        foreach ($endpoints as $endpoint) {
            $ch = curl_init($endpoint);
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'api-key: ' . $api_key,
                ),
                CURLOPT_TIMEOUT => 15,
                CURLOPT_SSL_VERIFYPEER => true,
            ));
            $resp = curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_error($ch);
            curl_close($ch);

            echo '<h3>GET ' . esc_html($endpoint) . ' — HTTP ' . $code . '</h3>';

            if ($err) {
                echo '<p style="color:red">cURL error: ' . esc_html($err) . '</p>';
                continue;
            }

            if ($code >= 200 && $code < 300) {
                $fields_data = json_decode($resp, true);

                // Filter to show only custom fields or picklist fields
                $custom_fields_found = array();
                if (is_array($fields_data)) {
                    foreach ($fields_data as $field) {
                        if (is_array($field) && (
                            !empty($field['isCustom']) ||
                            (isset($field['type']) && $field['type'] === 'PICKLIST') ||
                            (isset($field['displayName']) && (
                                stripos($field['displayName'], 'investment') !== false ||
                                stripos($field['displayName'], 'timeframe') !== false
                            ))
                        )) {
                            $custom_fields_found[] = $field;
                        }
                    }
                }

                if (!empty($custom_fields_found)) {
                    echo '<table class="widefat striped"><thead><tr><th>ID</th><th>Display Name</th><th>Internal Name</th><th>Type</th><th>Custom?</th><th>Picklist Options</th></tr></thead><tbody>';
                    foreach ($custom_fields_found as $f) {
                        $options_str = '';
                        if (!empty($f['picklistValues']) || !empty($f['options'])) {
                            $opts = !empty($f['picklistValues']) ? $f['picklistValues'] : $f['options'];
                            $opt_parts = array();
                            foreach ($opts as $opt) {
                                $opt_parts[] = (isset($opt['id']) ? $opt['id'] : '?') . ' = ' . (isset($opt['name']) ? $opt['name'] : (isset($opt['value']) ? $opt['value'] : '?'));
                            }
                            $options_str = implode('<br>', $opt_parts);
                        }
                        echo '<tr>';
                        echo '<td><strong>' . esc_html($f['id'] ?? $f['fieldId'] ?? '—') . '</strong></td>';
                        echo '<td>' . esc_html($f['displayName'] ?? $f['name'] ?? '—') . '</td>';
                        echo '<td>' . esc_html($f['internalName'] ?? $f['fieldName'] ?? '—') . '</td>';
                        echo '<td>' . esc_html($f['type'] ?? $f['fieldType'] ?? '—') . '</td>';
                        echo '<td>' . (!empty($f['isCustom']) ? '✅ Yes' : 'No') . '</td>';
                        echo '<td style="font-size:11px">' . ($options_str ?: '—') . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<p>No custom/picklist fields found with the filter. Showing full raw JSON response:</p>';
                    echo '<div style="' . $style_box . '">' . esc_html(json_encode($fields_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</div>';
                }
            } else {
                echo '<div style="' . $style_box . '">' . esc_html($resp) . '</div>';
            }
        }
    }

    // ── Basic lead test (no custom fields) ──
    if (isset($_GET['test_basic'])) {
        echo '<hr style="margin:24px 0"><h2>✅ Basic Lead Test (no customFieldValues)</h2>';

        $basic_payload = array(
            'firstName' => 'BasicTest',
            'lastName'  => 'NoCustomFields',
            'phoneNumbers' => array(array('type' => 'MOBILE', 'code' => 'IN', 'value' => '9999999991', 'dialCode' => '+91', 'primary' => true)),
            'emails' => array(array('type' => 'OFFICE', 'value' => 'basictest_' . time() . '@tykes.school', 'primary' => true)),
        );

        $json = json_encode($basic_payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $byte_length = mb_strlen($json, '8bit');

        $ch = curl_init('https://api.kylas.io/v1/leads/');
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'api-key: ' . $api_key,
                'Content-Length: ' . $byte_length,
            ),
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
        ));

        $resp = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $is_ok = ($code >= 200 && $code < 300);
        echo '<p style="font-size:16px;color:' . ($is_ok ? 'green' : 'red') . '"><strong>HTTP ' . $code . ' — ' . ($is_ok ? '✅ SUCCESS! Basic lead creation works.' : '❌ FAILED') . '</strong></p>';
        echo '<h3>JSON Sent</h3>';
        echo '<div style="' . $style_box . '">' . esc_html(json_encode(json_decode($json), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</div>';
        echo '<h3>Response</h3>';
        $decoded_resp = json_decode($resp, true);
        $rp = json_encode($decoded_resp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        echo '<div style="' . $style_box . '">' . esc_html($rp ?: $resp) . '</div>';

        if ($is_ok && is_array($decoded_resp)) {
            // Extract customFieldValues — these contain the REAL field IDs
            if (!empty($decoded_resp['customFieldValues'])) {
                echo '<h3 style="color:green">🎯 Custom Field IDs Found in Response!</h3>';
                echo '<p>These are the <strong>real API field IDs</strong> to use in customFieldValues:</p>';
                echo '<table class="widefat striped"><thead><tr><th>Field ID (API Key)</th><th>Field Name</th><th>Type</th><th>Current Value</th><th>Picklist Options</th></tr></thead><tbody>';
                foreach ($decoded_resp['customFieldValues'] as $key => $val) {
                    $field_name = '—';
                    $field_type = '—';
                    $options_str = '—';
                    $current_val = '—';

                    if (is_array($val)) {
                        $field_name = $val['displayName'] ?? $val['name'] ?? $val['fieldName'] ?? '—';
                        $field_type = $val['type'] ?? $val['fieldType'] ?? '—';
                        $current_val = is_array($val['value'] ?? null) ? json_encode($val['value']) : ($val['value'] ?? '—');

                        // Check for picklist options
                        if (!empty($val['picklistValues'])) {
                            $opt_parts = array();
                            foreach ($val['picklistValues'] as $opt) {
                                $opt_parts[] = '<strong>' . ($opt['id'] ?? '?') . '</strong> = ' . ($opt['name'] ?? $opt['value'] ?? '?');
                            }
                            $options_str = implode('<br>', $opt_parts);
                        }
                        if (!empty($val['options'])) {
                            $opt_parts = array();
                            foreach ($val['options'] as $opt) {
                                $opt_parts[] = '<strong>' . ($opt['id'] ?? '?') . '</strong> = ' . ($opt['name'] ?? $opt['value'] ?? '?');
                            }
                            $options_str = implode('<br>', $opt_parts);
                        }
                    } else {
                        $current_val = $val;
                    }

                    echo '<tr>';
                    echo '<td style="font-weight:bold;font-size:14px;color:#0073aa">' . esc_html($key) . '</td>';
                    echo '<td>' . esc_html($field_name) . '</td>';
                    echo '<td>' . esc_html($field_type) . '</td>';
                    echo '<td style="font-size:11px">' . esc_html($current_val) . '</td>';
                    echo '<td style="font-size:11px">' . ($options_str) . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
                echo '<p style="color:#cc0000;font-weight:bold">⬆️ Use the "Field ID (API Key)" values above instead of 2358096/2358097 in your code!</p>';
            } else {
                echo '<p>⚠️ No customFieldValues found in the response. The lead ID is: <strong>' . esc_html($decoded_resp['id'] ?? '?') . '</strong></p>';
                echo '<p>Try fetching this lead by ID below to see its full structure.</p>';
            }
        }
    }

    // ── Fetch lead by ID ──
    if (isset($_GET['fetch_lead_id']) && !empty($_GET['fetch_lead_id'])) {
        $lead_id = intval($_GET['fetch_lead_id']);
        echo '<hr style="margin:24px 0"><h2>🔍 Lead #' . $lead_id . ' Details</h2>';

        $ch = curl_init('https://api.kylas.io/v1/leads/' . $lead_id);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'api-key: ' . $api_key,
            ),
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
        ));
        $resp = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $lead_data = json_decode($resp, true);
        $rp = json_encode($lead_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        echo '<p>HTTP ' . $code . '</p>';

        if (!empty($lead_data['customFieldValues'])) {
            echo '<h3 style="color:green">🎯 Custom Field IDs from this Lead</h3>';
            echo '<table class="widefat striped"><thead><tr><th>Field Key</th><th>Raw Value</th></tr></thead><tbody>';
            foreach ($lead_data['customFieldValues'] as $key => $val) {
                echo '<tr><td><strong>' . esc_html($key) . '</strong></td><td><pre style="margin:0;font-size:11px">' . esc_html(json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre></td></tr>';
            }
            echo '</tbody></table>';
        }

        echo '<h3>Full Response</h3>';
        echo '<div style="' . $style_box . '">' . esc_html($rp ?: $resp) . '</div>';
    }

    // Fetch lead form
    echo '<hr style="margin:24px 0"><h2>🔍 Fetch Existing Lead by ID</h2>';
    echo '<form method="get" style="margin-bottom:24px">';
    // Preserve existing query params
    echo '<input type="hidden" name="post_type" value="tykes_lead">';
    echo '<input type="hidden" name="page" value="tykes-kylas-debug">';
    echo '<input type="number" name="fetch_lead_id" placeholder="Enter Kylas Lead ID" style="padding:6px;width:200px" value="' . esc_attr($_GET['fetch_lead_id'] ?? '') . '">';
    echo ' <button type="submit" class="button button-secondary">Fetch Lead</button>';
    echo '</form>';
    echo '<p style="color:#666">💡 Tip: Check your Kylas CRM for a lead that has Investment Range / Timeframe filled in. Enter its ID here to see the exact field structure.</p>';

    // ── Last submission ──
    echo '<hr style="margin:32px 0"><h2>📋 Last Form Submission Debug</h2>';
    $log = get_option('tykes_kylas_last_debug', array());

    if (empty($log)) {
        echo '<p>No submissions yet.</p></div>';
        return;
    }

    echo '<h3>Raw Values From Form</h3>';
    echo '<table class="widefat striped" style="max-width:900px"><thead><tr><th>Field</th><th>Value</th><th>Hex</th></tr></thead><tbody>';
    foreach ($log['post_values'] as $f => $v) {
        echo '<tr><td><strong>' . esc_html($f) . '</strong></td><td>' . esc_html($v) . '</td><td style="font-family:monospace;font-size:11px">' . esc_html(bin2hex($v)) . '</td></tr>';
    }
    echo '</tbody></table>';

    echo '<h3>Picklist Map Lookup</h3>';
    echo '<table class="widefat striped" style="max-width:900px"><thead><tr><th>Field</th><th>Value</th><th>ID</th><th>Status</th></tr></thead><tbody>';
    foreach ($log['map_results'] as $row) {
        $ok = $row['matched']
            ? '<span style="color:green">✅ Matched</span>'
            : '<span style="color:red">❌ NO MATCH</span>';
        echo '<tr><td><strong>' . esc_html($row['field']) . '</strong></td><td>' . esc_html($row['value']) . '</td><td>' . esc_html($row['id'] ?? '—') . '</td><td>' . $ok . '</td></tr>';
    }
    echo '</tbody></table>';

    echo '<h3>JSON Sent to Kylas</h3>';
    echo '<div style="' . $style_box . '">' . esc_html(json_encode(json_decode($log['json_sent']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</div>';

    echo '<h3>Kylas Response — HTTP ' . esc_html($log['http_code']) . '</h3>';
    $rp = json_encode(json_decode($log['response_body']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo '<div style="' . $style_box . '">' . esc_html($rp ?: $log['response_body']) . '</div>';

    echo '<p style="color:#666">Last updated: ' . esc_html($log['time']) . '</p>';
    if (isset($_GET['clear_debug'])) {
        delete_option('tykes_kylas_last_debug');
        echo '<p style="color:green">✅ Cleared.</p>';
    }
    echo '<a href="' . esc_url(add_query_arg('clear_debug', '1')) . '" class="button">Clear Debug Log</a>';
    echo '</div>';
}

// ─── AJAX Handler ───
function tykes_handle_lead_submission()
{
    check_ajax_referer('tykes_lead_nonce', 'nonce');

    $name = sanitize_text_field($_POST['full_name'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $city = sanitize_text_field($_POST['city'] ?? '');
    $investment = sanitize_text_field($_POST['investment_range'] ?? '');
    $timeframe = sanitize_text_field($_POST['timeframe'] ?? '');
    $source = sanitize_text_field($_POST['form_source'] ?? 'Unknown');

    if (empty($name) || empty($phone) || empty($email)) {
        wp_send_json_error(array('message' => 'Please fill in all required fields.'));
        return;
    }

    $post_id = wp_insert_post(array(
        'post_title' => $name . ' - ' . $phone,
        'post_type' => 'tykes_lead',
        'post_status' => 'publish',
    ));

    if (is_wp_error($post_id)) {
        wp_send_json_error(array('message' => 'Server error. Please try again.'));
        return;
    }

    update_post_meta($post_id, '_tykes_phone', $phone);
    update_post_meta($post_id, '_tykes_email', $email);
    update_post_meta($post_id, '_tykes_city', $city);
    update_post_meta($post_id, '_tykes_investment_range', $investment);
    update_post_meta($post_id, '_tykes_timeframe', $timeframe);
    update_post_meta($post_id, '_tykes_form_source', $source);
    update_post_meta($post_id, '_tykes_submitted_at', current_time('mysql'));

    $kylas_result = tykes_forward_to_kylas($name, $phone, $email, $city, $investment, $timeframe, $source);
    update_post_meta($post_id, '_tykes_kylas_status', $kylas_result);

    wp_send_json_success(array('message' => 'Thank you! We will get back to you shortly.'));
}
add_action('wp_ajax_tykes_submit_lead', 'tykes_handle_lead_submission');
add_action('wp_ajax_nopriv_tykes_submit_lead', 'tykes_handle_lead_submission');

// ─── Kylas CRM Integration ───
function tykes_forward_to_kylas($name, $phone, $email, $city, $investment, $timeframe, $source)
{

    $api_key = '33a8157b-b808-423a-a383-34e7a2529b73:5901';

    $parts = explode(' ', trim($name), 2);
    $first_name = $parts[0];
    $last_name = !empty($parts[1]) ? $parts[1] : '-';

    // ₹ = \xe2\x82\xb9  |  – (en-dash) = \xe2\x80\x93
    $investment_map = array(
        "\xe2\x82\xb930 Lakhs \xe2\x80\x93 \xe2\x82\xb940 Lakhs" => 2842319,
        "\xe2\x82\xb940 Lakhs \xe2\x80\x93 \xe2\x82\xb950 Lakhs" => 2842320,
        "Above \xe2\x82\xb950 Lakhs" => 2842321,
    );
    $timeframe_map = array(
        'Within 3 Months' => 2842322,
        '3-6 Months' => 2842323,
        'More Than 6 Months' => 2842324,
    );

    $investment_id = isset($investment_map[$investment]) ? (int) $investment_map[$investment] : null;
    $timeframe_id = isset($timeframe_map[$timeframe]) ? (int) $timeframe_map[$timeframe] : null;

    $debug = array(
        'time' => current_time('mysql'),
        'post_values' => compact('name', 'investment', 'timeframe', 'city', 'email', 'phone'),
        'map_results' => array(
            array('field' => 'Investment Range', 'value' => $investment, 'id' => $investment_id, 'matched' => $investment_id !== null),
            array('field' => 'Timeframe to Launch', 'value' => $timeframe, 'id' => $timeframe_id, 'matched' => $timeframe_id !== null),
        ),
    );

    // ── customFieldValues — real Kylas field keys discovered from API ──
    $custom_field_values = array();
    if ($investment_id) {
        $custom_field_values['cfInvestmentRange'] = $investment_id;
    }
    if ($timeframe_id) {
        $custom_field_values['cfTimeframeToLaunch'] = $timeframe_id;
    }

    $payload = array(
        'firstName' => $first_name,
        'lastName' => $last_name,
        'phoneNumbers' => array(
            array(
                'type' => 'MOBILE',
                'code' => 'IN',
                'value' => $phone,
                'dialCode' => '+91',
                'primary' => true,
            )
        ),
        'emails' => array(
            array(
                'type' => 'OFFICE',
                'value' => $email,
                'primary' => true,
            )
        ),
        'customFieldValues' => (object) $custom_field_values,
    );

    $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if ($json === false) {
        $err = 'JSON encode failed: ' . json_last_error_msg();
        $debug['json_sent'] = '{}';
        $debug['http_code'] = 0;
        $debug['response_body'] = $err;
        update_option('tykes_kylas_last_debug', $debug);
        return $err;
    }

    $debug['json_sent'] = $json;
    $byte_length = mb_strlen($json, '8bit');

    $ch = curl_init('https://api.kylas.io/v1/leads/');
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json',
            'api-key: ' . $api_key,
            'Content-Length: ' . $byte_length,
        ),
        CURLOPT_TIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_FOLLOWLOCATION => true,
    ));

    $resp_body = curl_exec($ch);
    $http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_err = curl_error($ch);
    curl_close($ch);

    if ($curl_err) {
        $debug['http_code'] = 0;
        $debug['response_body'] = 'cURL error: ' . $curl_err;
        update_option('tykes_kylas_last_debug', $debug);
        return 'Error: ' . $curl_err;
    }

    $debug['http_code'] = $http_code;
    $debug['response_body'] = $resp_body;
    update_option('tykes_kylas_last_debug', $debug);

    if ($http_code >= 200 && $http_code < 300)
        return 'Synced';

    $decoded = json_decode($resp_body, true);
    $msg = isset($decoded['message']) ? $decoded['message'] : substr($resp_body, 0, 200);
    return 'Failed (HTTP ' . $http_code . '): ' . $msg;
}