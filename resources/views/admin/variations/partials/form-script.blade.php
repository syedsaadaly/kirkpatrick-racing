<script>
    $(document).ready(function() {
        let optionCount = $('.option-row').length;

        $('#addOptionBtn').on('click', function() {
            const newRow = `
                <div class="option-row mb-3 p-3 border rounded">
                    <div class="row g-2">
                        <div class="col-md-7">
                            <label class="form-label">Option Value <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control"
                                   name="options[${optionCount}][value]"
                                   value=""
                                   placeholder="e.g., Small, Red, Cotton"
                                   required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Swatch Color</label>
                            <input type="color" class="form-control form-control-color w-100"
                                   name="options[${optionCount}][color]"
                                   value="#cccccc"
                                   title="Only used for Color-type variations">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-danger w-100 remove-option">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            $('#options-container').append(newRow);
            optionCount++;
        });

        $(document).on('click', '.remove-option', function() {
            if ($('.option-row').length > 1) {
                $(this).closest('.option-row').remove();
            } else {
                Swal.fire({
                    title: 'Cannot Remove',
                    text: 'At least one option is required.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6'
                });
            }
        });

        $('input[name="name"]').on('blur', function() {
            if (!$('input[name="slug"]').val()) {
                const name = $(this).val();
                if (name) {
                    const slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
                    $('input[name="slug"]').val(slug);
                }
            }
        });
    });
</script>
