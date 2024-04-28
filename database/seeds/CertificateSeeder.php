<?php

use Illuminate\Database\Seeder;
use App\Models\Backoffice\CertificateCategory;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            "Education Seminars and training workshop",
            "On the Job Training",
            "Healthcare Seminars & training",
            "The Pre-Internship Seminar and Pinning ceremony",
            "Government Seminars and Training program",
            "Turning Point Youth Camp",
            "Online webinar Training",
            "Entrepreneurship and Start up basics",
            "Capability building Seminar: Empowering Youth Leadership",
            "Arts Seminar and Training",
            "International Webinar on The role of women leaders in the new normal",
            "Stress Management and Mental Health Awareness",
            "Seminar workshop in Empowering coaches : Division - Wide Sports Training for all",
            "Career opportunities in (specific field)",
            "National leadership training workshop seminar",
            "Product knowledge seminar",
            "Fire & safety and gun safety seminar",
            "Business orientation seminar and training program",
            "Fire drill seminar and training program",
            "Karate sports and Arnis training seminar",
        ];

        foreach($categories as $index => $category){
            $newCategory = new CertificateCategory;
            $newCategory->name = $category;
            $newCategory->directory = 'images/certificates';
            $newCategory->filename = Str::slug($category).'.jpg';

            $newCategory->save();
        }
    }
}
