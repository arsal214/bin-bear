<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'key' 	=> 'header_logo',
                'value' => 'upload/images/settings/derivative-calculator-logo.webp',
            ],
            [
                'key' 	=> 'footer_logo',
                'value' => 'upload/images/settings/derivative-calculator-logo.webp',
            ],
            [
                'key'   => 'page_title_icon',
                'value' => 'upload/images/settings/derivative-favicon.png',
            ],
            [
                'key'   => 'bazigate_website_url',
                'value' => '',
            ],
            [
                'key'   => 'facebook_link',
                'value' => '',
            ],
            [
                'key'   => 'twitter_link',
                'value' => '',
            ],
            [
                'key'   => 'instagram_link',
                'value' => '',
            ],
            [
                'key'   => 'app_store_link',
                'value' => '',
            ],
            [
                'key'   => 'playstore_link',
                'value' => '',
            ],
            [
                'key'   => 'copyright_link',
                'value' => '',
            ],
            [
                'key'   => 'google_search_console_code',
                'value' => '',
            ],
            [
                'key'   => 'google_analytics_code',
                'value' => '',
            ],
            [
                'key'   => 'clarity_code',
                'value' => '',
            ],
            [
                'key'   => 'job_application_message',
                'value' => 'Thank you for submitting your resume',
            ],
            [
                'key'   => 'feadback_message',
                'value' => 'Thanks for filling out our form!',
            ],
            [
                'key'   => 'comment_message',
                'value' => 'Thanks for filling out our form!',
            ],
            [
                'key'   => 'newsletter_message',
                'value' => 'Thanks for reaching us.',
            ],
        ]);
    }
}
