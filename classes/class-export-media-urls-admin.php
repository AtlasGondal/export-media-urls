<?php

/**
 * Export Media URLs
 *
 * @package Export Media URLs
 *
    Copyright (c) 2020- Atlas Gondal (contact : https://atlasgondal.com/contact-me/)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

 **/

namespace Export_Media_URLs;

require_once(plugin_dir_path(__FILE__) . '../classes/constants.php');

$export_media_urls_admin = new ExportMediaURLsAdmin();

class ExportMediaURLsAdmin
{

    public const PLUGIN_TEXT_DOMAIN = 'export-media-urls';

    public function __construct()
    {
        add_action('admin_init', array($this, 'redirect_on_activation'));
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_footer_text', array($this, 'add_plugin_text_in_footer'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function redirect_on_activation()
    {

        if (!get_transient('export_media_urls_activation_redirect')) {
            return;
        }

        delete_transient('export_media_urls_activation_redirect');

        wp_safe_redirect(add_query_arg(array('page' => Constants::PLUGIN_SETTINGS_PAGE_SLUG), admin_url('tools.php')));
    }

    public function add_plugin_page()
    {

        add_management_page(
            'Export Media URLs',
            'Export Media URLs',
            Constants::PLUGIN_SETTINGS_PAGE_CAPABILITY,
            Constants::PLUGIN_SETTINGS_PAGE_SLUG,
            array($this, 'emu_settings_page')
        );
    }

    public function add_plugin_text_in_footer($footer_text)
    {

        if ($this->is_my_plugin_screen()) {

            $footer_text = 'Enjoyed <strong>Export Media URLs</strong>? Please leave us a <a href="https://wordpress.org/support/plugin/export-media-urls/reviews/?filter=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. We really appreciate your support! ';
        }

        return $footer_text;
    }

    public function enqueue_scripts($hook_suffix)
    {

        if ($hook_suffix !== Constants::PLUGIN_HOOK_SUFFIX) {
            return;
        }

        wp_enqueue_style('emu_style', plugin_dir_url(__FILE__) . '../assets/css/style.css', array(), '2.1', 'all');

        if (!wp_script_is('select2', 'registered')) {
            wp_register_script('select2', plugin_dir_url(__FILE__) . '../assets/js/select2.min.js', array('jquery'), '4.0.13', true);
            wp_register_style('select2css', plugin_dir_url(__FILE__) . '../assets/css/select2.min.css', false, '4.0.13', 'all');
        }

        if (!wp_script_is('select2', 'enqueued')) {
            wp_enqueue_script('select2');
            wp_enqueue_style('select2css');
        }

        wp_enqueue_script('emu_script', plugin_dir_url(__FILE__) . '../assets/js/script.js', array('jquery', 'select2'), '2.1', true);
    }

    public function emu_settings_page()
    {
        if (!current_user_can(Constants::PLUGIN_SETTINGS_PAGE_CAPABILITY)) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', Constants::PLUGIN_TEXT_DOMAIN));
        }

        $user_ids = array();
        $user_names = array();

        $users = get_users();

        foreach ($users as $user) {
            $user_ids[] = $user->data->ID;
            $user_names[] = $user->data->user_login;
        }

        $form_submitted = isset($_POST['form_submitted']) ? true : false;
        $selected_additional_data = isset($_POST['additional-data']) ? $_POST['additional-data'] : array('url');
        $selected_author = isset($_POST['post-author']) ? $_POST['post-author'] : 'all';
        $selected_date_range = isset($_POST['date-range']) ? $_POST['date-range'] : 'all';
        $selected_start_date = isset($_POST['start-date']) ? $_POST['start-date'] : '';
        $selected_end_date = isset($_POST['end-date']) ? $_POST['end-date'] : '';
        $selected_export_type = isset($_POST['export-type']) ? $_POST['export-type'] : 'dashboard';


?>

        <div class="wrap">

            <h2 align="center">Export Media URLs</h2>

            <div class="EMU-Wrapper">
                <div class="EMU-Main-Container postbox">
                    <div class="inside">

                        <form id="infoForm" method="post">

                            <table class="form-table">

                                <tr>

                                    <th><label>Additional Data:</label></th>

                                    <td>

                                        <?php
                                        
                                            foreach ($this->export_fields() as $key => $name) {
                                                $checked = in_array($key, $selected_additional_data) ? 'checked' : '';
                                                echo "<label><input type='checkbox' name='additional-data[]' value='$key' $checked /> $name</label><br />";
                                            }
                                        
                                        ?>

                                    </td>

                                </tr>

                                <tr>
                                    <th><label for="post-author">By Author:</label></th>
                                    <td>
                                        <select id="post-author" class="select2" name="post-author" required="required" style="width: 40%;">
                                            <option value="all" <?php echo $selected_author == 'all' ? 'selected' : '' ?>>All</option>
                                            <?php
                                            if (!empty($user_ids) && !empty($user_names)) {
                                                for ($i = 0; $i < count($user_ids); $i++) {
                                                    $is_author_selected = $selected_author == $user_ids[$i] ? 'selected' : '';
                                                    echo '<option value="' . esc_attr($user_ids[$i]) . '" ' . $is_author_selected . '>' . esc_html($user_names[$i]) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <th><label for="date-range">Date Range:</label></th>

                                    <td>
                                        <label><input type="radio" name="date-range" value="all" <?php echo $selected_date_range == 'all' ? 'checked' : ''; ?> required="required" onclick="hideRangeFields()" /> <?php echo esc_html__('All', self::PLUGIN_TEXT_DOMAIN); ?></label><br />
                                        <label><input type="radio" name="date-range" value="range" <?php echo $selected_date_range == 'range' ? 'checked' : ''; ?> required="required" onclick="showRangeFields()" /> <?php echo esc_html__('Between Dates', self::PLUGIN_TEXT_DOMAIN); ?></label><br />

                                        <div id="dateRange" style="display: <?php echo $selected_date_range == 'range' ? 'block' : 'none'; ?>">
                                            <?php echo esc_html__('From:', self::PLUGIN_TEXT_DOMAIN); ?> <input type="date" name="start-date" value="<?php echo $selected_start_date; ?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php echo esc_html__('To:', self::PLUGIN_TEXT_DOMAIN); ?> <input type="date" name="end-date" value="<?php echo $selected_end_date; ?>" />
                                        </div>
                                    </td>

                                </tr>

                                <tr>

                                    <th><label>Export Type:</label></th>

                                    <td>

                                        <label><input type="radio" name="export-type" value="csv" <?php echo $selected_export_type == 'csv' ? 'checked' : ''; ?> required="required" /> CSV File</label><br />
                                        <label><input type="radio" name="export-type" value="dashboard" <?php echo $selected_export_type == 'dashboard' ? 'checked' : ''; ?> required="required" /> Output here</label><br />

                                    </td>

                                </tr>

                                <tr>

                                    <td></td>

                                    <td>
                                        <?php wp_nonce_field('export_media_urls'); ?>
                                        <input type="hidden" name="form_submitted" value="1">
                                        <input type="submit" name="export" class="button button-primary" value="Export Now" />
                                    </td>

                                </tr>

                            </table>


                        </form>


                    </div>
                </div>
                <div class="EMU-Side-Container">
                    <div class="postbox">
                        <h3>Want to Support?</h3>
                        <div class="inside">
                            <p>If you enjoyed the plugin, and want to support:</p>
                            <ul>
                                <li>
                                    <a href="https://AtlasGondal.com/contact-me/?utm_source=self&utm_medium=wp&utm_campaign=export-media-urls&utm_term=hire-me" target="_blank">Hire me</a> on a project
                                </li>
                                <li>Buy me a Coffee
                                    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YWT3BFURG6SGS&source=url" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" /> </a>

                                </li>
                            </ul>
                            <hr>
                            <h3>Wanna say Thanks?</h3>
                            <ul>
                                <li>Leave <a href="https://wordpress.org/support/plugin/export-media-urls/reviews/?filter=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating
                                </li>
                                <li>Tweet me: <a href="https://twitter.com/atlas_gondal" target="_blank">@Atlas_Gondal</a>
                                </li>
                            </ul>
                            <hr>
                            <h3>Got a Problem?</h3>
                            <p>If you want to report a bug or suggest new feature. You can:</p>
                            <ul>
                                <li>Create <a href="https://wordpress.org/support/plugin/export-media-urls/" target="_blank">Support
                                        Ticket</a></li>

                                <li>Write me an <a href="https://AtlasGondal.com/contact-me/?utm_source=self&utm_medium=wp&utm_campaign=export-media-urls&utm_term=write-an-email" target="_blank">Email</a></li>
                            </ul>
                            <hr>
                            <h4 id="EMUDevelopedBy">Developed by: <a href="https://AtlasGondal.com/?utm_source=self&utm_medium=wp&utm_campaign=export-media-urls&utm_term=developed-by" target="_blank">Atlas Gondal</a></h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>

<?php

        if (isset($_POST['export'])) {

            if (isset($_REQUEST['_wpnonce'])) {
                $nonce = $_REQUEST['_wpnonce'];
                if (!wp_verify_nonce($nonce, 'export_media_urls')) {
                    echo "<div class='notice notice-error' style='width: 93%'>" . __('Sorry, invalid security token!', Constants::PLUGIN_TEXT_DOMAIN) . "</div>";
                    exit;
                }

                if (!empty($_POST['additional-data']) && !empty($_POST['post-author']) && !empty($_POST['export-type'])) {
                    $additional_data = array_map('sanitize_text_field', $_POST['additional-data']);
                    $post_author = sanitize_text_field($_POST['post-author']);
                    $date_range = sanitize_text_field($_POST['date-range']);

                    if ($date_range == 'range') {
                        $start_date = sanitize_text_field($_POST['start-date']);
                        $end_date = sanitize_text_field($_POST['end-date']);

                        if (empty($start_date) || empty($end_date)) {
                            echo "<div class='notice notice-error' style='width: 93%'>" . __('Please select both dates!', Constants::PLUGIN_TEXT_DOMAIN) . "</div>";
                            exit;
                        }

                        $start_date = strtotime($start_date);
                        $end_date = strtotime($end_date);

                        if ($start_date > $end_date) {
                            echo "<div class='notice notice-error' style='width: 93%'>" . __('Start date cannot be greater than end date!', Constants::PLUGIN_TEXT_DOMAIN) . "</div>";
                            exit;
                        }

                        $date_range = array(
                            'start_date' => date('Y-m-d', $start_date),
                            'end_date' => date('Y-m-d', $end_date),
                        );
                    } else {

                        $date_range = array(
                            'start_date' => '',
                            'end_date' => '',
                        );
                    }

                    $export_type = $_POST['export-type'];

                    $this->create_output($this->emu_generate_data($additional_data, $post_author, $date_range, $export_type), $export_type);
                } else {
                    echo __("Sorry, you did not select anything to export, Please <strong>Select Data </strong> you want to export, and then try again! :)", Constants::PLUGIN_TEXT_DOMAIN);
                    exit;
                }
            }
        } elseif (isset($_REQUEST['del']) && $_REQUEST['del'] == 'y') {
            if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'])) {
                echo __("You are not authorized to perform this action!", Constants::PLUGIN_TEXT_DOMAIN);
                exit();
            } else {
                $file = base64_decode($_REQUEST['f']);
                $path_info = pathinfo($file);
                $upload_dir = wp_upload_dir();

                if (($path_info['dirname'] == $upload_dir['path']) && ($path_info['extension'] == 'CSV')) {
                    echo !empty($file) ? (file_exists($file) ? (!unlink($file) ? "<div class='notice notice-error' style='width: 97%'></div>" . __("Unable to delete file, please delete it manually!", Constants::PLUGIN_TEXT_DOMAIN) : "<div class='updated' style='width: 97%'>" . __("You did great, the file was <strong>Deleted Successfully</strong>!", Constants::PLUGIN_TEXT_DOMAIN) . "</div>") : null) : "<div class='notice notice-error'>" . __("Missing file path.", Constants::PLUGIN_TEXT_DOMAIN) . "</div>";
                } else {
                    die("<div class='error' style='width: 95.3%; margin-left: 2px;'>" . __("Sorry, the file verification failed. Arbitrary file removal is not allowed.", Constants::PLUGIN_TEXT_DOMAIN) . "</div>");
                }
            }
        }
    }

    private function is_my_plugin_screen()
    {

        $current_screen = get_current_screen();

        if ($current_screen && false !== strpos($current_screen->id, Constants::PLUGIN_SETTINGS_PAGE_SLUG)) {
            return true;
        } else {
            return false;
        }
    }

    private function emu_is_checked($name, $value)
    {
        foreach ($name as $data) {
            if ($data == $value) {
                return true;
            }
        }

        return false;
    }

    private function emu_generate_data($additional_data, $post_author, $date_range, $export_type)
    {

        $data = array();
        $counter = 0;

        if(is_array($date_range)){

        }

        $line_break = $export_type == 'dashboard' ? "<br/>" : "";

        $query_media_urls = array(
            'post_type'         => 'attachment',
            'author'            => $post_author != 'all' ? $post_author : "",
            'post_status'       => 'inherit',
            'posts_per_page'    => -1,
            'date_query'        => array(
                array(
                    'after'     => $date_range['start_date'],
                    'before'    => $date_range['end_date'],
                    'inclusive' => true,
                )
            ),
        );

        $query_urls = new \WP_Query($query_media_urls);

        if (!$query_urls->have_posts()) {
            echo __("No result found in that range, please <strong>reselect and try again</strong>!", Constants::PLUGIN_TEXT_DOMAIN);
            exit();
        }

        foreach ($additional_data as $data_type) {
            if ($this->emu_is_checked($additional_data, $data_type)) {
                $counter = 0;
                while ($query_urls->have_posts()) {
                    $query_urls->the_post();

                    if (!isset($data[$data_type][$counter])) {
                        $data[$data_type][$counter] = null;
                    }

                    switch ($data_type) {
                        case 'id':
                            $data[$data_type][$counter] .= get_the_ID() . $line_break;
                            break;

                        case 'title':
                            $data[$data_type][$counter] .= get_the_title() . $line_break;
                            break;

                        case 'file_name':
                            $data[$data_type][$counter] .= wp_basename(get_attached_file(get_the_ID())) . $line_break;
                            break;

                        case 'file_size':
                            $data[$data_type][$counter] .= size_format(filesize(get_attached_file(get_the_ID()))). $line_break;
                            break;

                        case 'caption':
                            $data[$data_type][$counter] .= get_the_excerpt() . $line_break;
                            break;

                        case 'alt':
                            $attachment_id = get_the_ID();
                            $alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
                            $data[$data_type][$counter] .= $alt_text . $line_break;
                            break;

                        case 'description':
                            $data[$data_type][$counter] .= get_the_content() . $line_break;
                            break;

                        case 'url':
                            $data[$data_type][$counter] .= wp_get_attachment_url(get_the_ID());
                            break;

                        case 'date':
                            $data[$data_type][$counter] .= get_the_date() . $line_break;
                            break;

                        case 'type':
                            $data[$data_type][$counter] .= get_post_mime_type() . $line_break;
                            break;

                        default:
                            break;
                    }

                    $counter++;
                }

                wp_reset_postdata();
            }
        }

        return $data;
    }

    private function export_fields() {
     return array(
        'id'          => 'ID',
        'title'       => 'Title',
        'file_name'   => 'File Name',
        'file_size'   => 'File Size',
        'caption'     => 'Caption',
        'alt'         => 'Alt Text',
        'description' => 'Description',
        'url'         => 'URL',
        'date'        => 'Date Uploaded',
        'type'        => 'Type',
     );
    }

    private function get_data_headers($data)
    {
        $filtered_headers = [];

        $headers = $this->export_fields();

        foreach ($headers as $key => $name) {
            if (isset($data[$key])) {
                $filtered_headers[$key] = $name;
            }
        }

        return $filtered_headers;
    }

    private function create_output($data, $export_type)
    {
        $count = count($data['url']);
        $headers = $this->get_data_headers($data);

        switch ($export_type) {
            case 'csv':
                $upload_directory = wp_upload_dir();
                $csv_file_name = 'export-media-urls-' . rand(111111, 999999);
                $csv_file_path = $upload_directory['path'] . "/" . $csv_file_name . '.CSV';

                $file = fopen($csv_file_path, "w");
                if (!$file) {
                    return __("Error: Could not create file.", Constants::PLUGIN_TEXT_DOMAIN);
                }

                fprintf($file, "\xEF\xBB\xBF");

                if (!fputcsv($file, $headers)) {
                    fclose($file);
                    return __("Error: Could not write headers to file.", Constants::PLUGIN_TEXT_DOMAIN);
                }

                for ($i = 0; $i < $count; $i++) {
                    $csv_row = [];

                    foreach ($headers as $key => $val) {
                        $cellValue = isset($data[$key]) ? $data[$key][$i] : '';

                        if ($key === 'description') {
                            $cellValue = wp_kses_post($cellValue);
                        } elseif ($key === 'url') {
                            $cellValue = esc_url($cellValue);
                        } else {
                            $cellValue = sanitize_text_field($cellValue);
                        }

                        $csv_row[] = $cellValue;
                    }

                    if (!fputcsv($file, $csv_row)) {
                        fclose($file);
                        return __("Error: Could not write row to file.", Constants::PLUGIN_TEXT_DOMAIN);
                    }
                }

                fclose($file);

                $csv_download_url = $upload_directory['url'] . "/" . $csv_file_name . ".CSV";
                $success_message = __("Media Data Exported Successfully! <a href='$csv_download_url' target='_blank'><strong>Click here</strong></a> to Download.", Constants::PLUGIN_TEXT_DOMAIN);

                echo "<div class='updated' style='width: 97%'>$success_message</div>";

                echo "<div class='notice notice-warning' style='width: 97%'>" . __('Once you have downloaded the file, it is recommended to delete file from the server, for security reasons.', Constants::PLUGIN_TEXT_DOMAIN) . " <a href='" . wp_nonce_url(admin_url('tools.php?page=' . Constants::PLUGIN_SETTINGS_PAGE_SLUG . '&del=y&f=') . base64_encode($csv_file_path)) . "' ><strong>" . __('Click Here', Constants::PLUGIN_TEXT_DOMAIN) . "</strong></a> " . __('to delete the file. And don\'t worry, you can always regenerate anytime. :)', Constants::PLUGIN_TEXT_DOMAIN) . "</div>";

                break;

            default:
                $tableHtml = "<h1 align='center' style='padding: 10px 0;line-height: 1'><strong>" . __("Below is a list of Exported Media Data:", Constants::PLUGIN_TEXT_DOMAIN) . "</strong></h1>";
                $tableHtml .= "<table class='form-table' id='outputData'><tr><th>#";

                foreach ($headers as $key => $val) {
                    if (isset($data[$key])) {
                        $tableHtml .= "<th id='$key'>$val</th>";
                    }
                }

                $tableHtml .= "</tr>";

                for ($i = 0; $i < $count; $i++) {
                    $id = $i + 1;

                    $tableHtml .= "<tr><td>" . absint($id) . "</td>";

                    foreach ($headers as $key => $val) {
                        if (isset($data[$key])) {
                            $cellValue = $data[$key][$i];

                            if ($key === 'description') {
                                $cellValue = wp_kses_post($cellValue);
                            } elseif ($key === 'url') {
                                $cellValue = esc_url(strip_tags($cellValue));
                            } else {
                                $cellValue = sanitize_text_field($cellValue);
                            }

                            $tableHtml .= "<td>$cellValue</td>";
                        }
                    }

                    $tableHtml .= "</tr>";
                }

                $tableHtml .= "</table>";

                echo $tableHtml;
        }
    }
}
