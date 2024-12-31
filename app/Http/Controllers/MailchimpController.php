<?php

namespace App\Http\Controllers;

use DrewM\MailChimp\MailChimp;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MailchimpController extends Controller
{
    public function uploadFile()
    {
        return view('upload_file');
    }

    public function importContacts()
    {
        $csvData = Excel::toArray([], public_path('Step_1_Contact_Creation.xlsx'))[0];
        $mailchimp = new MailChimp('6382997d70641336f59834b26aa4c19e-us8');
        $csvData = array_slice($csvData, 0, 10);

        foreach ($csvData as $row) {
            $firstName = $row[0];
            $lastName = $row[1];
            $email = $row[2];

            $mailchimp->post('lists/6abc8f621b/members', [
                'email_address' => $email,
                'status' => 'subscribed',
                'merge_fields' => [
                    'FNAME' => $firstName,
                    'LNAME' => $lastName,
                ]
            ]);
        }

        return 'Contacts Imported Successfully';
    }

    public function updateContacts()
    {
        $csvData = Excel::toArray([], public_path('Step_1_Contact_Creation.xlsx'))[1];
        $mailchimp = new MailChimp('6382997d70641336f59834b26aa4c19e-us8');

        $csvData = array_slice($csvData, 0, 10);

        foreach ($csvData as $row) {
            $firstName = $row[0];
            $lastName = $row[1];
            $email = $row[2];
            $address = $row[3];
            $phone = $row[4];
            $title = $row[5];
            $tags = explode(',', $row[6]);
            $birthday = $row[7];
            $streetAddress = $row[8];
            $city = $row[9];
            $state = $row[10];
            $zipCode = $row[11];
            $country = $row[12];
            $countryFull = $row[13];
            $subscriberHash = md5(strtolower($email));

            // Update member data
            $mailchimp->put("lists/6abc8f621b/members/{$subscriberHash}", [
                'email_address' => $email,
                'address' => $address,
                'phone' => $phone,
                'title' => $title,
                'birthday' => $birthday,
                'streetAddress' => $streetAddress,
                'city' => $city,
                'state' => $state,
                'zipCode' => $zipCode,
                'country' => $country,
                'countryFull' => $countryFull,
                'status_if_new' => 'subscribed',
                'merge_fields' => [
                    'FNAME' => $firstName,
                    'LNAME' => $lastName,
                ],
                'tags' => $tags
            ]);
        }

        return 'Contacts Updated Successfully';
    }

    public function updateContactsWithDifferentTags()
    {
        $csvData = Excel::toArray([], public_path('Step_1_Contact_Creation.xlsx'))[2];
        $mailchimp = new MailChimp('6382997d70641336f59834b26aa4c19e-us8');

        $csvData = array_slice($csvData, 0, 10);

        foreach ($csvData as $row) {
            $firstName = $row[0];
            $lastName = $row[1];
            $email = $row[2];
            $tags = explode(',', $row[6]);  // Assuming tags are comma-separated

            $subscriberHash = md5(strtolower($email));

            $mailchimp->put("lists/6abc8f621b/members/{$subscriberHash}", [
                'email_address' => $email,
                'status_if_new' => 'subscribed',
                'merge_fields' => [
                    'FNAME' => $firstName,
                    'LNAME' => $lastName,
                ],
                'tags' => $tags
            ]);
        }

        return 'Contacts Updated with New Tags Successfully';
    }

    public function downloadContacts()
    {
        $mailchimp = new MailChimp('6382997d70641336f59834b26aa4c19e-us8');
        $csvData = [];

        // Start with the first page
        $offset = 0;
        $limit = 100; // Mailchimp's default page size is 100, but you can adjust as needed
        do {
            $result = $mailchimp->get('lists/6abc8f621b/members', [
                'offset' => $offset,
                'count'  => $limit
            ]);

            // Check if we received any data
            if (isset($result['members'])) {
                foreach ($result['members'] as $contact) {
                    $tags = !empty(array_column($contact['tags'], 'name')) ? implode(',', array_column($contact['tags'], 'name')) : '';

                    $csvData[] = [
                        'email_address' => $contact['email_address'],
                        'first_name' => $contact['merge_fields']['FNAME'],
                        'last_name' => $contact['merge_fields']['LNAME'],
                        'address' => isset($contact['merge_fields']['ADDRESS']['addr1']) ? $contact['merge_fields']['ADDRESS']['addr1'] : '',
                        'phone_number' => isset($contact['merge_fields']['PHONE']) ? $contact['merge_fields']['PHONE'] : '',
                        'birthday' => $contact['merge_fields']['BIRTHDAY'],
                        'company' => $contact['merge_fields']['COMPANY'],
                        'tags' => $tags,
                        'last_changed' => $contact['last_changed'],
                        'mailchimp_id' => $contact['id'],
                    ];
                }
            }

            $offset += $limit;
        } while (isset($result['members']) && count($result['members']) === $limit);

        $csvFile = fopen(storage_path('app/mailchimp_contacts.csv'), 'w');
        fputcsv($csvFile, ['email_address', 'first_name', 'last_name', 'address', 'phone_number', 'birthday', 'company', 'tags', 'last_changed', 'mailchimp_id']);

        foreach ($csvData as $data) {
            fputcsv($csvFile, $data);
        }

        fclose($csvFile);

        return response()->download(storage_path('app/mailchimp_contacts.csv'));
    }
}
