@extends('layouts.backend.app')

@section('title', 'Category')

@push('css')

@endpush

@section('content')
    <div class="container-fluid">

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            CATEGORY TAG
                        </h2>
                    </div>
                    <div class="body">
                        <form action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name',     $category->name) }}">
                                    <label class="form-label">Category name</label>
                                </div>
                            </div>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="file" id="image" name="image" class="form-control">
                                </div>
                            </div>

                            <a class="btn btn-danger m-t-15 waves-effect" href="{{ route('admin.category.index') }}">BACK</a>
                            <button type="submit" class="btn btn-primary m-t-15 waves-effect">SUBMIT</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

@endpush