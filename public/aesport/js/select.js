$(document).ready(function () {
    // select region live tv
    $('.select-region').select2({
        placeholder: 'Select an option',
        dropdownAutoWidth: true,
        width: 200,
        selectionCssClass: "select-custom-live-tv",
        dropdownCssClass: "dropdown-select-custom-live-tv",
        templateResult: function (state) {
            if (!state.id) {
                return state.text;
            }
            var $state = $('<span><img src="' + state.icon + '" class="img-flag" /> ' + state.text + '</span>');
            return $state;
        },
        minimumResultsForSearch: Infinity,
        data: [
            {
                "id": 1,
                "text": "United States",
                icon: "./assets/icons/icon-la-liga.png"
            },
            {
                "id": 2,
                "text": "United Kingdom",
                icon: "./assets/icons/icon-ligue-1.png"
            }
        ],
        templateSelection: function (state) {
            var $span = $("<span><img src='" + state.icon + "'/> " + state.text + "</span>");
            return $span;
        }
    });

    // select match toaday
    $('.select-match-today').select2({
        placeholder: 'Select an option',
        dropdownAutoWidth: true,
        width: 200,
        selectionCssClass: "select-custom-match-today",
        dropdownCssClass: "dropdown-select-custom-match-today",
        templateResult: function (state) {
            if (!state.id) {
                return state.text;
            }
            var $state = $('<span><img src="' + state.icon + '" class="img-flag" /> ' + state.text + '</span>');
            return $state;
        },
        minimumResultsForSearch: Infinity,
        data: [
            {
                "id": 1,
                "text": "Premier league",
                icon: "./assets/icons/icon-la-liga.png"
            },
            {
                "id": 2,
                "text": "Bundesliga",
                icon: "./assets/icons/icon-ligue-1.png"
            }
        ],
        templateSelection: function (state) {
            var $span = $("<span><img src='" + state.icon + "'/> " + state.text + "</span>");
            return $span;
        }
    });
});