jQuery("#add-image").click(() => {
    const index = +jQuery('#widget-pictures-counter').val();
    const tmpl  = jQuery("#project_pictures").data("prototype").replace(/__name__/g, index);
    
    jQuery("#project_pictures").append(tmpl);

    jQuery('#widget-pictures-counter').val(index + 1);

    handleDeleteButtons();
});

function handleDeleteButtons() {
    jQuery('button[data-action="delete"]').click(function() {
        const target = jQuery(this).data("target");
        jQuery(target).remove();
    });
}

function updateCounter(id) {
    const count = +jQuery("#project_"+id+" div.form-group").length;
    jQuery('#widget-'+id+'-counter').val(count);
}

updateCounter('pictures');
handleDeleteButtons();

jQuery("#project_technologies").removeClass('form-select');
new SlimSelect({
    select: '#project_technologies'
})
