define("ajax", ["require", "exports", "axios"], function (require, exports, axios_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.getMetaValue = void 0;
    function getMetaValue() {
        axios_1.default.post(ajaxurl, {
            action: 'my_ajax_function'
        }).then(response => {
            // Aquí va el código para manejar la respuesta de la llamada AJAX
            console.log('El valor del meta es:', response.data);
        }).catch(error => {
            console.log('Error al obtener el valor del meta:', error);
        });
    }
    exports.getMetaValue = getMetaValue;
});
define("index", ["require", "exports", "ajax"], function (require, exports, ajax_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    const oldPokedex = document.getElementById('show_old_pokedex');
    if (oldPokedex) {
        oldPokedex.addEventListener('click', ajax_1.getMetaValue);
    }
});
