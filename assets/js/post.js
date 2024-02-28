import TomSelect from "tom-select"
import TinyMCE from "tinymce"
import AirDatepicker from "air-datepicker"
import localeFr from "air-datepicker/locale/fr"

export function post() {
    let render = {
        no_results: function (data, escape) {
            return '<div class="no-results">Aucun résultat pour "' + escape(data.input) + '"</div>';
        }
    };
    new TomSelect('#post_primaryTag', {
        placeholder: 'Sélectionner le tag principal',
        render: render
    });
    new TomSelect('#post_tags', {
        placeholder: 'Ajouter un tag',
        render: render
    });

    TinyMCE.init({
        selector: '.tinymce',
        plugins: 'advlist link image lists code codesample',
        toolbar: 'undo redo | styles | bold italic underline | link image forecolor codesample | alignleft aligncenter alignright alignjustify',
        height: 550
    });

    new AirDatepicker('.datepicker', {
        locale: localeFr,
        dateFormat: 'dd/MM/yyyy',
        timepicker: true,
        timeFormat: 'HH:mm:00',
        buttons: ['today', 'clear'],
        autoClose: true,
        position: "top left"
    });
}