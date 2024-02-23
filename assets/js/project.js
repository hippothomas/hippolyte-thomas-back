import TomSelect from "tom-select"
import TinyMCE from "tinymce"

export function project() {
    document.getElementById('add-image').addEventListener('click', () => {
        const index = +document.getElementById('widget-pictures-counter').value;
        let tmpl = document.createElement('div');
        tmpl.innerHTML = document.getElementById("project_pictures").getAttribute("data-prototype").replace(/__name__/g, index);

        document.getElementById("project_pictures").appendChild(tmpl);
        document.getElementById('widget-pictures-counter').value = index + 1;

        handleDeleteButtons();
    });

    function handleDeleteButtons() {
        document.querySelectorAll('button[data-action="delete"]').forEach((el) => {
            el.addEventListener('click', (e) => {
                const target = (e.target.tagName === 'I') ? e.target.parentElement : e.target;
                document.querySelector(target.getAttribute('data-target')).remove();
            });
        });
    }

    function updateCounter(id) {
        const count = document.querySelectorAll("#project_" + id + " div.form-group").length;
        document.getElementById('widget-' + id + '-counter').value = count;
    }

    updateCounter('pictures');
    handleDeleteButtons();

    new TomSelect('#project_technologies', {
        placeholder: 'Ajouter une technologie',
        render: {
            no_results: function (data, escape) {
                return '<div class="no-results">Aucun résultat pour "' + escape(data.input) + '"</div>';
            }
        }
    });

    TinyMCE.init({
        selector: '.tinymce',
        plugins: 'advlist link image lists code codesample',
        toolbar: 'undo redo | styles | bold italic underline | link image forecolor codesample | alignleft aligncenter alignright alignjustify'
    });
}