@extends('Dashboard.Layouts.Master')
@section('css')
    {{-- Start TinyMCE Js --}}

    {{-- End TinyMCE Js --}}
    <link rel="stylesheet" href="{{ URL::asset('DataTable/dataTables.bootstrap5.min.css') }}">
@endsection
@section('title_page')
    Article
@endsection
@section('one')
    Article
@endsection
@section('two')
    Article
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">


            </div>
            <div class="card">
                <div class="card-body">
                    <x-alerts></x-alerts>
                    <h5 class="card-title">Edit Article</h5>


                    <!-- General Form Elements -->
                    <form method="POST" id="my-form" action="{{ url('dashboard/article/update') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Title:</label>
                            <input type="text" name="main_title" required class="form-control"
                                value="{{ $article->title }}" id="recipient-name">
                            <input type="hidden" name="article_id" value="{{ $article->id }}">
                            <x-inline_alert name='title'></x-inline_alert>
                        </div>



                        <fieldset class="row mb-3">
                            <legend class="col-form-label col-sm-2 pt-0">Most famous </legend>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="most_famous" id="gridRadios0"
                                        value="1" {{ $article->most_famous ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gridRadios0">
                                        Yes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="most_famous" id="gridRadios20"
                                        value="0" {{ !$article->most_famous ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gridRadios20">
                                        No
                                    </label>
                                </div>


                            </div>
                        </fieldset>
                        <fieldset class="row mb-3">
                            <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="gridRadios1"
                                        value="1" {{ $article->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gridRadios1">
                                        Active
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="gridRadios2"
                                        value="0" {{ !($article->status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gridRadios2">
                                        Not Active
                                    </label>
                                </div>


                            </div>
                        </fieldset>

                        <fieldset class="row mb-3">
                            <legend class="col-form-label col-sm-2 pt-0">Show In Home Page </legend>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="home_page" id="home_page1"
                                        value="1" {{ $article->home_page ? 'checked' : '' }}>
                                    <label class="form-check-label" for="home_page1">
                                        Yes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="home_page" id="home_page2"
                                        value="0" {{ !($article->home_page) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="home_page2">
                                        No
                                    </label>
                                </div>

                            </div>
                        </fieldset>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">SubCategorie:</label>
                            <select class="form-select" required name="articlesubcategorie_id">
                                <option readonly disabled>Open this select menu</option>

                                @foreach ($subCategories as $subCategorie)
                                    <option @selected($article->articlesubcategorie_id == $subCategorie->id) value="{{ $subCategorie->id }}">
                                        {{ $subCategorie->name }}</option>
                                @endforeach

                            </select>
                            <x-inline_alert name='articlesubcategorie_id'></x-inline_alert>
                        </div>





                        <label for="recipient-name" class="col-form-label">Descreption :</label>
                        <div class="mb-3 parent_text_editor sortable" id="sortable"
                            style="display: flex;justify-content: space-between;align-items: center;margin-right: -10px;flex-wrap: wrap;">

                            @foreach ($article->descreptionArticles as $descreption)
                                {{-- __________________________________ strat row --}}
                                <div style="width: 100%;display: flex;justify-content: space-between;align-items: center;"
                                    class="main_row sortable-item">
                                    <div class="row rounded cancel"
                                        style="background: #8080801a;margin-bottom: 20px;width: 1000px;margin-left: 0px;">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroup-sizing-default">Title</span>
                                            </div>
                                            <input type="text" class="form-control" aria-label="Default"
                                                value="{{ $descreption->title }}" name="title[]"
                                                aria-describedby="inputGroup-sizing-default">
                                        </div>
                                        <textarea name="froala_content[]" id="editor" class="editor">
                                        {!! $descreption->content !!}

                                        </textarea>
                                    </div>
                                    <div class="actions" style="display: flex;flex-direction: column;">
                                        <input type="number" name="order[]" min="1"
                                            value="{{ $descreption->order }}" class="form-control order"
                                            style="width: 75.6px;margin-bottom: 40px;margin-top: 0px;">
                                        <button type="button" style="height: 50px;margin-bottom: 10px;"
                                            class="btn btn-outline-primary add_row">Add</button>
                                        <button type="button" style="height: 50px;"
                                            class="btn btn-outline-danger delete_row">Delete</button>

                                    </div>
                                </div>
                                {{-- __________________________________ strat row --}}
                            @endforeach
                        </div>
                        {{-- ___________________________ --}}



                        <div class="mb-3" style="display: flex; justify-content: space-between;">
                            <label for="formFileLg" class="form-label">Image:</label>
                            <input class="form-control form-control-lg" id="formFileLg"
                                style="width: 533.6px; height: 37.6px;" name="image_file" type="file">
                            <img style="width: 79.6px; height: 79.6px;"
                                src="{{ URL::asset('Backend/Uploades/Articles/' . $article->image_file) }}"
                                class="img-thumbnail image_name_modal" alt="Image User">
                            <x-inline_alert name='image_file'></x-inline_alert>
                        </div>

                        {{-- ___________________________ --}}


                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Submit Button</label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Submit Form</button>
                            </div>
                        </div>

                    </form><!-- End General Form Elements -->

                </div>
            </div>


        </div>




        </div>
        {{-- end model ---- ______________________________________________________________ --}}
    @endsection
    @section('js')
        @include('Dashboard.pages.Articles.Article.scripts')
        <script>
            $(document).ready(function() {
                $('#formFileLg').on('change', function() {
                    var file = this.files[0];

                    if (file) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            $('.image_name_modal').attr('src', e.target.result);
                        }

                        reader.readAsDataURL(file);
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                function hideFroalaMessage() {
                    $('div[style="z-index:9999;width:100%;position:relative"]').hide();
                }

                hideFroalaMessage();

                $(document).on('click keyup', function() {
                    hideFroalaMessage();
                });
            });
        </script>
    @endsection
