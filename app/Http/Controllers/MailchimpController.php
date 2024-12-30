<?php

namespace App\Http\Controllers;

use DrewM\MailChimp\MailChimp;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MailchimpController extends Controller
{
    public function importContacts()
    {
        $csvData = Excel::toArray([], storage_path('app/Step_1_Contact_Creation.xlsx'))[0];
        $mailchimp = new MailChimp('4129d2249f3791d096d6056afb91889e-us8');
        $csvData = array_slice($csvData,0,10);

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
        $csvData = Excel::toArray([], storage_path('app/Step_1_Contact_Creation.xlsx'))[1];
        $mailchimp = new MailChimp('4129d2249f3791d096d6056afb91889e-us8');

        $csvData = array_slice($csvData,0,10);

        foreach ($csvData as $row) {
            $firstName = $row[0];
            $lastName = $row[1];
            $email = $row[2];
            $tags = explode(',', $row[6]);  // Assuming tags are comma-separated

            $subscriberHash = md5(strtolower($email));  // Mailchimp requires MD5 hash of the email

            // Update member data
            $mailchimp->put("lists/6abc8f621b/members/{$subscriberHash}", [
                'email_address' => $email,
                'status_if_new' => 'subscribed',
                'merge_fields' => [
                    'FNAME' => $firstName,
                    'LNAME' => $lastName,
                ],
                'tags' => $tags  // Adding tags
            ]);
        }

        return 'Contacts Updated Successfully';
    }

    public function updateContactsWithDifferentTags()
    {
        $csvData = Excel::toArray([], storage_path('app/Step_1_Contact_Creation.xlsx'))[2];
        $mailchimp = new MailChimp('4129d2249f3791d096d6056afb91889e-us8');
        
        $csvData = array_slice($csvData,0,10);

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
        $mailchimp = new MailChimp('4129d2249f3791d096d6056afb91889e-us8');
        $result = $mailchimp->get('lists/6abc8f621b/members');

        $contacts = $result['members'];
        $csvData = [];

        foreach ($contacts as $contact) {
            $csvData[] = [
                'email_address' => $contact['email_address'],
                'first_name' => $contact['merge_fields']['FNAME'],
                'last_name' => $contact['merge_fields']['LNAME'],
                'mailchimp_id' => $contact['id'],
            ];
        }

        // Convert to CSV
        $csvFile = fopen(storage_path('app/mailchimp_contacts.csv'), 'w');
        fputcsv($csvFile, ['email_address', 'first_name', 'last_name', 'mailchimp_id']);

        foreach ($csvData as $data) {
            fputcsv($csvFile, $data);
        }

        fclose($csvFile);

        return response()->download(storage_path('app/mailchimp_contacts.csv'));
    }





    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
