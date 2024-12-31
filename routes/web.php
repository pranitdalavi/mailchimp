<?php

use App\Http\Controllers\MailchimpController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Returns blade to upload file
Route::get('/upload-file', [MailchimpController::class, 'uploadFile']);

//Import csv in mailchimp
Route::post('/import-csv', [MailchimpController::class, 'importContacts'])->name('csv.import');

//Update imported csv with new fields
Route::post('/update-csv', [MailchimpController::class, 'updateContacts'])->name('csv.update');

//Update imported existing csv with new data
Route::post('/update-tags-csv', [MailchimpController::class, 'updateContactsWithDifferentTags'])->name('csv.update.tags');

//Download csv file
Route::post('/download-csv', [MailchimpController::class, 'downloadContacts'])->name('csv.download');
