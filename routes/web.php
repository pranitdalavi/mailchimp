<?php

use App\Http\Controllers\MailchimpController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/import-csv', [MailchimpController::class, 'importContacts'])->name('import.csv.form');

Route::get('/update-csv', [MailchimpController::class, 'updateContacts'])->name('import.csv.form');

Route::get('/update-tags-csv', [MailchimpController::class, 'updateContactsWithDifferentTags'])->name('import.csv.form');

Route::get('/download-csv', [MailchimpController::class, 'downloadContacts'])->name('import.csv.form');
