@include('admin.layouts.partials.header')
@include('admin.layouts.partials.sidebar')
<div class="content">
    @yield('content')

    <footer class="footer">
        <div class="row g-0 justify-content-between fs-10 mt-4 mb-3">
            <div class="col-12 col-sm-auto text-center">
                <p class="mb-0 text-600">
                    {!! \App\Models\Setting::getValue('dashboard_copyright', 'All Rights Reserved | ' . date('Y') . ' &copy;') !!}
                </p>
            </div>
        </div>
    </footer>

    <div class="modal fade" id="authentication-modal" tabindex="-1" role="dialog"
         aria-labelledby="authentication-modal-label" aria-hidden="true">
        <div class="modal-dialog mt-6" role="document">
            <div class="modal-content border-0">
                <div class="modal-header px-5 position-relative modal-shape-header bg-shape">
                    <div class="position-relative z-1">
                        <h4 class="mb-0 text-white" id="authentication-modal-label">Register</h4>
                        <p class="fs-10 mb-0 text-white">Please create your free Falcon account</p>
                    </div>
                    <button class="btn-close position-absolute top-0 end-0 mt-2 me-2" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body py-4 px-5">
                    <form>
                        <div class="mb-3">
                            <label class="form-label" for="modal-auth-name">Name</label>
                            <input class="form-control" type="text" autocomplete="on" id="modal-auth-name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="modal-auth-email">Email address</label>
                            <input class="form-control" type="email" autocomplete="on" id="modal-auth-email">
                        </div>
                        <div class="row gx-2">
                            <div class="mb-3 col-sm-6">
                                <label class="form-label" for="modal-auth-password">Password</label>
                                <input class="form-control" type="password" autocomplete="on" id="modal-auth-password">
                            </div>
                            <div class="mb-3 col-sm-6">
                                <label class="form-label" for="modal-auth-confirm-password">Confirm Password</label>
                                <input class="form-control" type="password" autocomplete="on"
                                       id="modal-auth-confirm-password">
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="modal-auth-register-checkbox">
                            <label class="form-label" for="modal-auth-register-checkbox">I accept the <a
                                    href="#!">terms </a>and <a class="white-space-nowrap" href="#!">privacy
                                    policy</a></label>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary d-block w-100 mt-3" type="submit" name="submit">Register
                            </button>
                        </div>
                    </form>
                    <div class="position-relative mt-5">
                        <hr>
                        <div class="divider-content-center">or register with</div>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-sm-6"><a class="btn btn-outline-google-plus btn-sm d-block w-100" href="#"><span
                                    class="fab fa-google-plus-g me-2" data-fa-transform="grow-8"></span> google</a>
                        </div>
                        <div class="col-sm-6"><a class="btn btn-outline-facebook btn-sm d-block w-100" href="#"><span
                                    class="fab fa-facebook-square me-2" data-fa-transform="grow-8"></span> facebook</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="eventDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border"></div>
        </div>
    </div>
    <div class="modal fade" id="addEventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border">
                <form id="addEventForm" autocomplete="off">
                    <div class="modal-header px-x1 bg-body-tertiary border-bottom-0">
                        <h5 class="modal-title">Create Schedule</h5>
                        <button class="btn-close me-n1" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-x1">
                        <div class="mb-3">
                            <label class="fs-9" for="eventTitle">Title</label>
                            <input class="form-control" id="eventTitle" type="text" name="title" required="required">
                        </div>
                        <div class="mb-3">
                            <label class="fs-9" for="eventStartDate">Start Date</label>
                            <input class="form-control datetimepicker" id="eventStartDate" type="text"
                                   required="required" name="startDate" placeholder="yyyy/mm/dd hh:mm"
                                   data-options='{"static":"true","enableTime":"true","dateFormat":"Y-m-d H:i"}'>
                        </div>
                        <div class="mb-3">
                            <label class="fs-9" for="eventEndDate">End Date</label>
                            <input class="form-control datetimepicker" id="eventEndDate" type="text" name="endDate"
                                   placeholder="yyyy/mm/dd hh:mm"
                                   data-options='{"static":"true","enableTime":"true","dateFormat":"Y-m-d H:i"}'>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="eventAllDay" name="allDay">
                            <label class="form-check-label" for="eventAllDay">All Day
                            </label>
                        </div>
                        <div class="mb-3">
                            <label class="fs-9">Schedule Meeting</label>
                            <div><a class="btn badge-subtle-success btn-sm" href="#!"><span
                                        class="fas fa-video me-2"></span>Add video conference link</a></div>
                        </div>
                        <div class="mb-3">
                            <label class="fs-9" for="eventDescription">Description</label>
                            <textarea class="form-control" rows="3" name="description" id="eventDescription"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="fs-9" for="eventLabel">Label</label>
                            <select class="form-select" id="eventLabel" name="label">
                                <option value="" selected="selected">None</option>
                                <option value="primary">Business</option>
                                <option value="danger">Important</option>
                                <option value="success">Personal</option>
                                <option value="warning">Must Attend</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-end align-items-center bg-body-tertiary border-0"><a
                            class="me-3 text-600" href="events/create-an-event.html">More options</a>
                        <button class="btn btn-primary px-4" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('admin.layouts.partials.footer')
