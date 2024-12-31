@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Contact Creation -->
            <div id="contact-creation" class="upload-form">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Contact Creation</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('csv.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- <div class="form-group">
                                <label for="resume">Choose Resume File</label>
                                <input type="file" class="form-control" id="resume" name="file">
                            </div> -->
                            <button type="submit" class="btn btn-primary mt-3">Click Here!</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Updation -->
            <div id="contact-updation" class="upload-form">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Contact Updation</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('csv.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- <div class="form-group">
                                <input type="file" class="form-control" id="students" name="file">
                            </div> -->
                            <button type="submit" class="btn btn-primary mt-3">Click Here!</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Updation - Different Tags -->
            <div id="contact-updation-diff-tags" class="upload-form">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Contact Updation - Different Tags</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('csv.update.tags') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- <div class="form-group">
                                <label for="family">Choose Family Sheet File</label>
                                <input type="file" class="form-control" id="family" name="file">
                            </div> -->
                            <button type="submit" class="btn btn-primary mt-3">Click Here!</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Download CSV -->
            <div id="download-csv" class="upload-form">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Download CSV</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('csv.download') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <button type="submit" class="btn btn-primary mt-3">Download CSV</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection