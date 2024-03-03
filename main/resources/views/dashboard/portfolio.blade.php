@extends('layouts.dashboard')
@section('title', 'Portfolio')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>

    <li class="breadcrumb-item active">Portfolio</li>
@endsection
@section('dashboardMain')
    <h2 class="my-3 mb-3">Add Portfolio</h2>
    <!-- Button trigger modal -->
    <div class="text-center">
        <button type="button" id="addPortfolioBtn" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#addPortfolio">
            Add Portfolio
        </button>
        <!-- Modal -->
        <div class="modal fade" id="addPortfolio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('addPortfolio') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add portfolio</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="p-3 w-100 text-center">
                                <div id="preview-images">
                                </div>
                                <br>
                                <label class="mb-5" for="uploadImage" class="w-100 text-center mb-2">
                                    <i style="font-size:48px;" class="bi bi-upload w-100"></i><br>
                                    <span>Upload Product Images</span>
                                </label>
                                <input draggable="true" multiple placeholder="select image"
                                    class="d-none w-100 border my-1 form-control border-secondary" type="file"
                                    name="update_img[]" id="uploadImage">
                                <input type="hidden" name="image_url" value="0">
                                <input name="name"
                                    class="mb-1 w-100 border-secondary-subtle input-text rounded px-3 form-control border-secondary"
                                    type="text" placeholder="Portfolio title here" name="title" id="">
                                <input
                                    class="mb-1 w-100 border-secondary-subtle input-text rounded px-3 form-control border-secondary"
                                    type="text" placeholder="Project long title" name="project_title">
                                <input
                                    class="mb-1 w-100 border-secondary-subtle input-text rounded px-3 form-control border-secondary"
                                    type="text" placeholder="Project description" name="project_description">
                                <input
                                    class="mb-1 w-100 border-secondary-subtle input-text rounded px-3 form-control border-secondary"
                                    type="text" placeholder="Company name" name="client">
                                <input
                                    class="mb-1 w-100 border-secondary-subtle input-text rounded px-3 form-control border-secondary"
                                    type="date" placeholder="Project date" name="project_date">
                                <input
                                    class="mb-1 w-100 border-secondary-subtle input-text rounded px-3 form-control border-secondary"
                                    type="text" placeholder="Project link" name="project_url">
                                <select class="form-select form-select-lg w-100 border-secondary-subtle my-1"
                                    name="category" id="">
                                    <option selected>Select Category</option>
                                    @foreach ($categories as $key => $category)
                                        <option>{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                                <div class="">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                            {{-- <button type="button" class="">Save changes</button> --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <form action="{{ route('updateDescription') }}" method="post">
        @method('put')
        @csrf
        <h5 class="mt-0">Update Description</h5>
        <div class="form-floating d-flex">
            @foreach ($sectionDescriptions as $item)
                @if ($item->title == 'portfolio')
                    <div class="row col-12">
                        <div class="col-10">
                            <textarea name="section_description" class="form-control border-secondary" placeholder="Description"
                                id="floatingTextarea" style="height: 100px;">{{ $item->description }}</textarea>
                            <input type="hidden" name="section_id" value="{{ $item->id }}">
                            <input class="" type="hidden" name="id" value="{{ $item->id }}">
                        </div>
                        <div class="col-2">
                            <button class="w-100 py-2 rounded-2 btn btn-primary mt-4" type="submit">Update</button>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </form>
    <h2 class="my-3 mb-3">Update Portfolio Section </h2>
    <table class="table text-center">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Image</th>
                <th scope="col">Title</th>
                <th scope="col">Long Title</th>
                <th scope="col">Description</th>
                <th scope="col">Client</th>
                <th scope="col">Date</th>
                <th scope="col">Url</th>
                <th scope="col">Category</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($portfolios as $key => $item)
                {{-- {{ $item }} --}}
                <tr class="align-middle">
                    <th scope="row">{{ $key + 1 }}</th>
                    <td>
                        <div class="">
                            <img style="height: 80px"
                                src="{{ url('assets/img/home/portfolio/' . $item->images[0]->image_url) }}"
                                class="img-fluid img-thumbnail" alt="">
                        </div>
                    </td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->project_title }}</td>
                    <td class="text-justify" title="{{ $item->project_description }}">
                        {{ Str::limit($item->project_description, 80, '...') }} </td>
                    <td>{{ $item->client }}</td>
                    <td>{{ Carbon\Carbon::parse($item->project_date)->format('d M, Y') }}</td>
                    <td>{{ $item->project_url }}</td>
                    <td>{{ $item->category }}</td>
                    <td>

                        <div class="d-flex gap-2 justify-content-center">
                            <form action="{{ route('deletePortfolio', [$item->id]) }}" method="post">
                                @method('delete')
                                @csrf
                                <input class="btn btn-danger" type="submit" value="Delete">
                            </form>
                            <!-- Button trigger modal -->
                            <button id="updateImageBtn" type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal{{ $item->id }}">
                                Edit
                            </button>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <form action="{{ route('updatePortfolio', [$item->id]) }}" method="post"
                                    enctype="multipart/form-data">
                                    @method('put')
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update portfolio</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="p-3">
                                                <div id="preview-update-images" class="">
                                                    <table class="w-100 ">
                                                        <tbody>
                                                            @foreach ($item->images as $image)
                                                                <tr>
                                                                    <th scope="row"><img
                                                                            style="height: 120px; widows: 80px;"
                                                                            src="{{ url('assets/img/home/portfolio/' . $image->image_url) }}"
                                                                            class="img-fluid img-thumbnail"
                                                                            alt="">
                                                                    </th>
                                                                    <form
                                                                        action="{{ route('updatePortfolioSingleImage', [$item->id]) }}"
                                                                        method="post" enctype="multipart/form-data">
                                                                        @method('put')
                                                                        @csrf
                                                                        <td>
                                                                            <label class="mb-5 w-100 text-center mb-2"
                                                                                for="updateImage{{ $item->id }}">
                                                                                <i style="font-size:48px;"
                                                                                    class="bi bi-upload w-100"></i><br>
                                                                                <span>Upload Product Images</span>
                                                                            </label>
                                                                            <input placeholder="select image"
                                                                                class="w-100 border my-1 form-control border-secondary"
                                                                                type="file" name="updateImage{{ $item->id }}"
                                                                                id="updateImage{{ $item->id }}">
                                                                        </td>
                                                                        <input type="hidden" name="122"
                                                                            value="123">
                                                                        <td>

                                                                            <button type="submit"
                                                                                class="btn btn-primary">
                                                                                Update Image</button>

                                                                    </form>
                    </td>
                    <td><a href="{{ route('deletePortfolioSingleImage', [$image->id]) }}"
                            class="btn btn-danger">DELETE</a></td>
                </tr>
                <div class="row row-cols-4">
                </div>
            @endforeach
        </tbody>
    </table>
    </div>
    <input name="name" class="w-100 border-secondary-subtle input-text rounded px-3 form-control border-secondary"
        type="text" name="title" id="" value="{{ $item->name }}">
    <input class="mb-1 w-100 border-secondary-subtle input-text rounded px-3 form-control border-secondary" type="text"
        placeholder="Project long title" name="project_title" value="{{ $item->project_title }}">
    <input class="mb-1 w-100 border-secondary-subtle input-text rounded px-3 form-control border-secondary" type="text"
        placeholder="Project description" name="project_description" value="{{ $item->project_description }}">
    <textarea class="mb-1 w-100 border-secondary-subtle input-text rounded px-3 form-control border-secondary"
        name="project_description" id="" cols="30" rows="10" placeholder="Project description">{{ $item->project_description }}</textarea>
    <input class="mb-1 w-100 border-secondary-subtle input-text rounded px-3 form-control border-secondary" type="text"
        placeholder="Company name" name="client" value="{{ $item->client }}">
    <input class="mb-1 w-100 border-secondary-subtle input-text rounded px-3 form-control border-secondary" type="date"
        placeholder="Project date" name="project_date" value="{{ $item->project_date }}">
    <input class="mb-1 w-100 border-secondary-subtle input-text rounded px-3 form-control border-secondary" type="text"
        placeholder="Project link" name="project_url" value="{{ $item->project_url }}">
    <select class="form-select form-select-lg w-100 border-secondary-subtle my-1" name="category" id="">
        <option selected>Select one</option>
        @foreach ($categories as $key => $category)
            <option @if ($category->category_name == $item->category) selected @endif>
                {{ $category->category_name }}</option>
        @endforeach
    </select>
    <div class="">
    </div>
    </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
        {{-- <button type="button" class="">Save changes</button> --}}
    </div>
    </div>
    </form>
    </div>
    </div>
    </td>
    </tr>
    @endforeach
    </tbody>
    </table>
    </div>

@endsection
