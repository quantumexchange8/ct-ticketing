@extends('layouts.masterMember')
@section('content')

<!-- Support start -->
<section class="section-sm" id="Support">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Default Repeater</h4>
                        <p class="text-muted mb-0">An interface to add and remove a repeatable group of input elements.</p>
                    </div><!--end card-header-->
                    <div class="card-body">
                        <form method="POST" class="form-horizontal well">
                            <fieldset>
                                <div class="repeater-default">
                                    <div data-repeater-list="car">
                                        <div data-repeater-item="">
                                            <div class="form-group row d-flex align-items-end">

                                                <div class="col-sm-4">
                                                    <input type="file" name="" class="form-control">
                                                </div><!--end col-->

                                                <div class="col-sm-1">
                                                    <span data-repeater-delete="" class="btn btn-danger btn-sm">
                                                        <span class="far fa-trash-alt mr-1"></span> Delete
                                                    </span>
                                                </div><!--end col-->
                                            </div><!--end row-->
                                        </div><!--end /div-->
                                    </div><!--end repet-list-->
                                    <div class="form-group mb-0 row">
                                        <div class="col-sm-12">
                                            <span data-repeater-create="" class="btn btn-secondary btn-sm">
                                                <span class="fas fa-plus"></span> Add
                                            </span>
                                            <input type="submit" value="Submit" class="btn btn-primary btn-sm">
                                        </div><!--end col-->
                                    </div><!--end row-->
                                </div> <!--end repeter-->
                            </fieldset><!--end fieldset-->
                        </form><!--end form-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>
    <!-- end page content -->
</section>
<!-- Support end -->

@endsection
