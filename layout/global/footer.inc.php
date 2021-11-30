<div id="footer" class="footer">
	<p>&copy; Copyright <? Echo date("Y");?> <a href="https://scrillas.com">Bomalick Web Development</a> | All rights reserved</p>
</div>
<script>
    $('.expand-search').click(function(){
        $('.adv-search').slideToggle('slow');
    });

    flatpickr(".datepicker", {
        altFormat: "m/d/Y",
        altInput: "true"
    });

    flatpickr(".datetimepicker", {
        altFormat: "m/d/Y h:i:S K",
        altInput: "true",
        enableTime: "true",
        enableSeconds: "true"
    });

    $(document).ready(function() {
        $('.js-example-basic-single').select2({
            theme: "trident"
        }
        );
        $('.js-example-basic-multiple').select2({
            theme: "trident"
        });
    });
    </script>