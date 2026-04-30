/**
 * Club de Lecture - JavaScript
 * Interactivité : notation par étoiles, recherche instantanée
 */

document.addEventListener('DOMContentLoaded', function () {

    // ========== Notation par étoiles ==========
    const starRating = document.getElementById('starRating');
    if (starRating) {
        const stars = starRating.querySelectorAll('.star');
        const noteInput = document.getElementById('noteInput');

        stars.forEach(function (star) {
            // Clic pour sélectionner la note
            star.addEventListener('click', function () {
                const value = parseInt(this.getAttribute('data-value'));
                noteInput.value = value;
                updateStars(stars, value);
            });

            // Survol pour prévisualiser
            star.addEventListener('mouseenter', function () {
                const value = parseInt(this.getAttribute('data-value'));
                highlightStars(stars, value);
            });
        });

        // Quitter le survol : revenir à la note sélectionnée
        starRating.addEventListener('mouseleave', function () {
            const currentValue = parseInt(noteInput.value) || 0;
            updateStars(stars, currentValue);
        });
    }

    /**
     * Met à jour l'affichage des étoiles
     */
    function updateStars(stars, value) {
        stars.forEach(function (s) {
            const v = parseInt(s.getAttribute('data-value'));
            if (v <= value) {
                s.classList.add('star-active');
            } else {
                s.classList.remove('star-active');
            }
        });
    }

    /**
     * Surligne les étoiles au survol
     */
    function highlightStars(stars, value) {
        stars.forEach(function (s) {
            const v = parseInt(s.getAttribute('data-value'));
            if (v <= value) {
                s.classList.add('star-active');
            } else {
                s.classList.remove('star-active');
            }
        });
    }

    // ========== Recherche instantanée de livres ==========
    const searchInput = document.getElementById('searchBooks');
    if (searchInput) {
        const booksGrid = document.getElementById('booksGrid');
        if (booksGrid) {
            const bookCards = booksGrid.querySelectorAll('.book-card');

            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase().trim();

                bookCards.forEach(function (card) {
                    const searchData = card.getAttribute('data-search') || '';
                    if (query === '' || searchData.includes(query)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    }

});
