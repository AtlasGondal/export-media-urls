<?php

/*
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

 */

$export_media_urls_admin = new ExportMediaURLsAdmin();

class ExportMediaURLsAdmin {

    public function __construct() {


        add_action( 'admin_init', array( $this, 'redirect_on_activation' ) );
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_footer_text', array( $this, 'add_plugin_text_in_footer' ) );
//        add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_wp_admin_style' ) );
//        add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );
//        add_action( 'admin_notices', array( $this, 'generate_notice' ) );

    }

    public function redirect_on_activation() {

        if ( ! get_transient( 'export_media_urls_activation_redirect' ) ) {
            return;
        }

        delete_transient( 'export_media_urls_activation_redirect' );

        wp_safe_redirect( add_query_arg( array( 'page' => 'extract-media-urls-settings' ), admin_url( 'tools.php' ) ) );

    }

    public function add_plugin_page() {

        add_management_page(
            'Export Media URLs',
            'Export Media URLs',
            'manage_options',
            'extract-media-urls-settings',
            array( $this, 'emu_settings_page')
        );

    }

    public function add_plugin_text_in_footer( $footer_text ) {

        if ( $this->is_my_plugin_screen() ) {

            $footer_text = 'Enjoyed <strong>Export Media URLs</strong>? Please leave us a <a href="https://wordpress.org/support/plugin/export-media-urls/reviews/?filter=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. We really appreciate your support! ';

        }

        return $footer_text;
    }

    public function emu_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
        }

        $user_ids = array();
        $user_names = array();

        $users = get_users();

        foreach ( $users as $user ) {
            $user_ids[] = $user->data->ID;
            $user_names[] = $user->data->user_login;
        }

        ?>

        <style>.EMU-Wrapper{display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap;overflow:hidden}.EMU-Main-Container{width:75%;margin-bottom:0}.EMU-Side-Container{width:24%}.EMU-Side-Container .postbox:first-child{margin-left:20px;padding-top:15px}.eaucolumns{float:left;display:-webkit-flex;display:-ms-flexbox;display:flex;margin-top:5px}.EMU-Side-Container .postbox{margin-bottom:0;float:none}.EMU-Side-Container .inside{margin-bottom:0}.EMU-Side-Container hr{width:70%;margin:10px auto}.EMU-Side-Container h3{cursor:default;text-align:center;font-size:16px;margin:1px 0}.EMU-Side-Container li{list-style:disclosure-closed;margin-left:25px}.EMU-Side-Container li a img{display:inline-block;vertical-align:middle}#EMUDevelopedBy{text-align:center}#outputData{border-collapse:collapse;width:98%;border:1px solid #ccc;margin:0;padding:0;table-layout:fixed}#outputData tr{border:1px solid #ddd;padding:.35em}#outputData tr:nth-child(even){background-color:#fff}#outputData tr:hover{background-color:#ddd}#outputData th{background-color:#000;color:#fff;letter-spacing:.1em}#outputData td,#outputData th{text-align:left;padding:8px;word-wrap:break-word}#outputData th:first-child{width:4%}#outputData #id{width:6%}#outputData #title{width:20%}#outputData #url{width:50%}#outputData #date{width:12%}#outputData #type{width:8%}@media screen and (max-width:800px){.EMU-Main-Container{width:90%;margin-left:auto;margin-right:auto}.EMU-Side-Container{width:90%;margin-left:auto;margin-right:auto;padding-top:20px}.EMU-Side-Container .postbox:first-child{margin-left:0}#infoForm label{padding-bottom:32px}#outputData{border:0;width:86%;margin-left:auto;margin-right:auto}#outputData thead{border:none;clip:rect(0 0 0 0);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute;width:1px}#outputData tr{border-bottom:3px solid #ddd;display:block;margin-bottom:.625em}#outputData th{width:95%!important}#outputData td,#outputData th{border-bottom:1px solid #ddd;display:block;font-size:.8em}#outputData td::before{float:left;font-weight:700;text-transform:uppercase}#outputData td:last-child{border-bottom:0}}</style>

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

                                        <label><input type="checkbox" name="additional-data[]" value="id"/>
                                            Media ID</label><br/>
                                        <label><input type="checkbox" name="additional-data[]" value="title"/>
                                            Media Title</label><br/>
                                        <label><input type="checkbox" name="additional-data[]" value="url" checked />
                                            Media URL</label><br/>
                                        <label><input type="checkbox" name="additional-data[]" value="date"/> Date Uploaded</label><br/>

                                        <label><input type="checkbox" name="additional-data[]" value="type"/> Media Type</label><br/>

                                    </td>

                                </tr>

                                <tr>

                                    <th><label>By Author:</label></th>

                                    <td>

                                        <label><input type="radio" name="post-author" checked value="all"
                                                      required="required"/> All</label><br/>
                                        <?php

                                        if (!empty($user_ids) && !empty($user_names)) {
                                            for ($i = 0; $i < count($user_ids); $i++) {
                                                echo '<label><input type="radio" name="post-author" value="' . $user_ids[$i] . '" required="required" /> ' . $user_names[$i] . '</label><br>';
                                            }
                                        }
                                        ?>

                                    </td>

                                </tr>

                                <tr>

                                    <th><label>Export Type:</label></th>

                                    <td>

                                        <label><input type="radio" name="export-type" value="csv" required="required"/> CSV File</label><br/>
                                        <label><input type="radio" name="export-type" value="dashboard" required="required" checked />
                                            Output here</label><br/>

                                    </td>

                                </tr>

                                <tr>

                                    <td></td>

                                    <td>
                                        <input type="submit" name="export" class="button button-primary"
                                               value="Export Now"/>
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
                                    <a href="https://AtlasGondal.com/contact-me/?utm_source=self&utm_medium=wp&utm_campaign=export-media-urls&utm_term=hire-me"
                                       target="_blank">Hire me</a> on a project
                                </li>
                                <li>Buy me a Coffee
                                    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YWT3BFURG6SGS&source=url" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif"/> </a>

                                </li>
                            </ul>
                            <hr>
                            <h3>Wanna say Thanks?</h3>
                            <ul>
                                <li>Leave <a
                                            href="https://wordpress.org/support/plugin/export-media-urls/reviews/?filter=5#new-post"
                                            target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating
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

                                <li>Write me an <a
                                            href="https://AtlasGondal.com/contact-me/?utm_source=self&utm_medium=wp&utm_campaign=export-media-urls&utm_term=write-an-email"
                                            target="_blank">Email</a></li>
                            </ul>
                            <hr>
                            <h4 id="EMUDevelopedBy">Developed by: <a
                                        href="https://AtlasGondal.com/?utm_source=self&utm_medium=wp&utm_campaign=export-media-urls&utm_term=developed-by"
                                        target="_blank">Atlas Gondal</a></h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <?php

        if (isset($_POST['export'])) {
            if( !empty($_POST['additional-data']) && !empty($_POST['post-author']) && !empty($_POST['export-type']) ) {
                $additional_data = $_POST['additional-data'];
                $post_author = $_POST['post-author'];
                $export_type = $_POST['export-type'];

                $this->create_output( $this->emu_generate_data($additional_data, $post_author, $export_type), $export_type );

            } else {
                echo "Sorry, you did not select anything to export, Please <strong>Select Data </strong> you want to export, and then try again! :)";
                exit;
            }
        }

    }

    private function is_my_plugin_screen () {

        $current_screen = get_current_screen();

        if ( $current_screen && false !== strpos( $current_screen->id, 'extract-media-urls-settings') ) {
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

    private function emu_generate_data ($additional_data, $post_author, $export_type) {

        $data = array();
        $counter = 0;

        $line_break = $export_type == 'dashboard' ? "<br/>" : "";

        $query_media_urls = array(
            'post_type'         => 'attachment',
            'author'            => $post_author != 'all' ? $post_author : "",
            'post_status'       => 'inherit',
            'posts_per_page'    => - 1,
        );

        $query_urls = new WP_Query( $query_media_urls );

        if (!$query_urls->have_posts()) {
            echo "no result found in that range, please <strong>reselect and try again</strong>!";
            exit();
        }

        if ($this->emu_is_checked($additional_data, 'id')) {

            while ($query_urls->have_posts()):

                $data['id'][$counter] = (isset($data['id'][$counter]) ? "" : null);

                $query_urls->the_post();
                $data['id'][$counter] .= get_the_ID() . $line_break;

                $counter++;

            endwhile;

            $counter = 0;

        }

        if ($this->emu_is_checked($additional_data, 'title')) {

            while ($query_urls->have_posts()):

                $data['title'][$counter] = (isset($data['title'][$counter]) ? "" : null);

                $query_urls->the_post();
                $data['title'][$counter] .= get_the_title() . $line_break;

                $counter++;

            endwhile;

            $counter = 0;

        }

        if ($this->emu_is_checked($additional_data, 'url')) {

            while ($query_urls->have_posts()):

                $data['url'][$counter] = (isset($data['url'][$counter]) ? "" : null);

                $query_urls->the_post();
                $data['url'][$counter] .= wp_get_attachment_url( get_the_ID() ) . $line_break;

                $counter++;

            endwhile;

            $counter = 0;

        }

        if ($this->emu_is_checked($additional_data, 'date')) {

            while ($query_urls->have_posts()):

                $data['date'][$counter] = (isset($data['date'][$counter]) ? "" : null);

                $query_urls->the_post();
                $data['date'][$counter] .= get_the_date() . $line_break;

                $counter++;

            endwhile;

            $counter = 0;

        }

        if ($this->emu_is_checked($additional_data, 'type')) {

            while ($query_urls->have_posts()):

                $data['type'][$counter] = (isset($data['type'][$counter]) ? "" : null);

                $query_urls->the_post();
                $data['type'][$counter] .= get_post_mime_type() . $line_break;

                $counter++;

            endwhile;

            $counter = 0;

        }

        return $data;

    }

    private function create_output ( $data, $export_type ) {

        $count = 0;
        foreach ( $data as $url ) {
            $count = count( $url );
        }

        switch ($export_type) {
            case 'csv':

                $upload_directory = wp_upload_dir();
                $csv_file_name = 'export-media-urls-' . rand(111111, 999999);

                $csv_data = '';
                $headers = array();

                $file = $upload_directory['path']. "/" . $csv_file_name . '.CSV';
                $myfile = @fopen($file, "w") or die("Unable to create a file on your server!");
                fprintf($myfile, "\xEF\xBB\xBF");

                $headers[] = 'ID';
                $headers[] = 'Title';
                $headers[] = 'URLs';
                $headers[] = 'Date Uploaded';
                $headers[] = 'Media Type';

                fputcsv($myfile, $headers);

                for ($i = 0; $i < $count; $i++) {
                    $csv_data = array(
                        isset($data['id']) ? $data['id'][$i] : "",
                        isset($data['title']) ? $data['title'][$i] : "",
                        isset($data['url']) ? $data['url'][$i] : "",
                        isset($data['date']) ? $data['date'][$i] : "",
                        isset($data['type']) ? $data['type'][$i] : "",
                    );

                    fputcsv($myfile, $csv_data);
                }

                fclose($myfile);

                echo "<div class='updated' style='width: 97%'>Media Data Exported Successfully! <a href='" . $upload_directory['url'] . "/" . $csv_file_name . ".CSV' target='_blank'><strong>Click here</strong></a> to Download.</div>";
                break;

            default:

                echo "<h1 align='center' style='padding: 10px 0;line-height: 1'><strong>Below is a list of Exported Media Data:</strong></h1>";
                echo "<table class='form-table' id='outputData'>";
                echo "<tr><th>#</th>";
                echo isset($data['id']) ? "<th id='id'>Media ID</th>" : null;
                echo isset($data['title']) ? "<th id='title'>Title</th>" : null;
                echo isset($data['url']) ? "<th id='url'>URLs</th>" : null;
                echo isset($data['date']) ? "<th id='date'>Date Uploaded</th>" : null;
                echo isset($data['type']) ? "<th id='type'>Media Type</th>" : null;

                echo "</tr>";

                for ($i = 0; $i < $count; $i++) {

                    $id = $i + 1;

                    echo "<tr>";

                    echo "<td>" . $id . "</td>";
                    echo isset($data['id']) ? "<td>".$data['id'][$i]."</td>" : null;
                    echo isset($data['title']) ? "<td>" . $data['title'][$i] . "</td>" : null;
                    echo isset($data['url']) ? "<td>" . $data['url'][$i] . "</td>" : null;
                    echo isset($data['date']) ? "<td>" . $data['date'][$i] . "</td>" : null;
                    echo isset($data['type']) ? "<td>" . $data['type'][$i] . "</td>" : null;

                    echo "</tr>";
                }

                echo "</table>";

                break;
        }

    }

}