</div>
</main>

<div id="global-loader" style="display: none;">
    <div class="loader-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h5 class="mt-3 text-white">Processing, please wait...</h5>
    </div>
</div>

@stack('custom-modals')

@if (session()->has('success'))
    <div style="position: fixed;top: 1rem;right: 1rem;z-index: 9999999 !important;">
        <div class="toast fade" id="liveToast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <strong class="me-auto">Success</strong>
                <small>Just now</small>
                <div data-bs-theme="dark">
                    <button class="btn-close" type="button" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
            <div class="toast-body">{{ session('success') }}</div>
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div style="position: fixed; top: 1rem; right: 1rem; z-index: 2000;">
        <div class="toast fade" id="liveToast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-danger text-white">
                <strong class="me-auto">Error</strong>
                <small>Just now</small>
                <div data-bs-theme="dark">
                    <button class="btn-close" type="button" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
            <div class="toast-body">{{ session('error') }}</div>
        </div>
    </div>
@endif

<!-- ===============================================-->
<!--    JavaScripts-->
<!-- ===============================================-->
<script src="{{asset('admin/vendors/jquery/jquery.min.js')}}"></script>
<script src="{{asset('admin/vendors/popper/popper.min.js')}}"></script>
<script src="{{asset('admin/vendors/bootstrap/bootstrap.min.js')}}"></script>
<script src="{{asset('admin/vendors/anchorjs/anchor.min.js')}}"></script>
<script src="{{asset('admin/vendors/is/is.min.js')}}"></script>
<script src="{{asset('admin/vendors/echarts/echarts.min.js')}}"></script>
<script src="{{asset('admin/vendors/draggable/draggable.bundle.legacy.js')}}"></script>
<script src="{{asset('admin/vendors/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('admin/vendors/fullcalendar/index.global.min.js')}}"></script>
<script src="{{asset('admin/vendors/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('admin/vendors/dayjs/dayjs.min.js')}}"></script>
<script src="{{asset('admin/assets/js/page-builder.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('admin/vendors/fontawesome/all.min.js')}}"></script>
<script src="{{asset('admin/vendors/lodash/lodash.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-polyfills/0.1.43/polyfill.min.js"></script>
<script src="{{asset('admin/vendors/list.js/list.min.js')}}"></script>
<script src="{{ asset('admin/assets/js/theme.js')}}"></script>
<script src="{{ asset('admin/vendors/sortablejs/Sortable.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="{{asset('admin/assets/js/common.js')}}"></script>

@stack('custom-script')

@if (session()->has('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var toastEl = document.getElementById('liveToast');
            var toast = new bootstrap.Toast(toastEl);
            toast.show(); // ✅ automatically show toast
        });
    </script>
@endif

@if (session()->has('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var toastEl = document.getElementById('liveToast');
            var toast = new bootstrap.Toast(toastEl);
            toast.show(); // ✅ automatically show toast
        });
    </script>
@endif

</body>

</html>
