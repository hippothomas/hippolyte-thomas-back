import AirDatepicker from "air-datepicker"
import localeFr from "air-datepicker/locale/fr"

export function apikey() {
    new AirDatepicker('.datepicker', {
        locale: localeFr,
        dateFormat: 'dd/MM/yyyy',
        buttons: ['today', 'clear'],
        autoClose: true
    });
}