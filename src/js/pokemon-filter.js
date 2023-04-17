var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
let typeSelected = '';
const pokemonTypes = () => __awaiter(void 0, void 0, void 0, function* () {
    const response = yield fetch('https://pokeapi.co/api/v2/type?limit=5');
    const data = yield response.json();
    const types = data.results.map((type) => type.name);
    return types;
});
const initFilter = () => {
    const filterWrapper = document.querySelector('#filter-wrapper');
    if (filterWrapper) {
        pokemonTypes().then((types) => {
            setFilterGroup(types);
        });
        document.addEventListener('DOMContentLoaded', () => {
            getPosts();
        });
    }
};
const setFilterGroup = (types) => {
    const typesContainer = document.querySelector('#types-container');
    if (typesContainer) {
        typesContainer.innerHTML = '';
        types.forEach((type) => {
            const typeCapitalized = type.charAt(0).toUpperCase() + type.slice(1);
            const typeElement = document.createElement('button');
            typeElement.classList.add('btn', 'btn-primary');
            typeElement.textContent = typeCapitalized;
            typeElement.addEventListener('click', () => {
                filterByType(type);
            });
            typesContainer.appendChild(typeElement);
        });
    }
};
const filterByType = (type) => {
    typeSelected = type;
    getPosts(1, typeSelected);
};
const getPosts = (page = 1, filterType = '') => __awaiter(void 0, void 0, void 0, function* () {
    const perPage = 6;
    let request = `/wp-json/wp/v2/pokemon?per_page=${perPage}&page=${page}`;
    if (filterType) {
        request += `&type_slug=${filterType}`;
    }
    const response = yield fetch(vars.homeurl + request);
    const posts = yield response.json();
    const totalPages = Number(response.headers.get('x-wp-totalpages'));
    const gridContainer = document.querySelector('#grid-container');
    if (gridContainer) {
        gridContainer.innerHTML = '';
        if (posts.length) {
            posts.forEach((post) => {
                const postTitle = post.title.rendered;
                const postImg = post.acf.pokemon_image;
                const primaryType = post.acf.pokemon_primary_type;
                const secondaryType = post.acf.pokemon_secondary_type;
                const postElement = document.createElement('div');
                postElement.classList.add('col-md-2', 'my-2', 'border');
                postElement.dataset.primaryType = (primaryType) ? primaryType : '';
                postElement.dataset.secondaryType = (secondaryType) ? secondaryType : '';
                postElement.innerHTML = `
                    <img src="${postImg}" class="card-img-top" alt="${postTitle}">
                    <div class="card-body">
                        <h5 class="card-title">${postTitle}</h5>
                    </div>
                `;
                gridContainer.appendChild(postElement);
            });
        }
        else {
            const postElement = document.createElement('div');
            postElement.classList.add('alert', 'alert-warning', 'my-2');
            postElement.innerHTML = 'There are no pokemon with that Type';
            gridContainer.appendChild(postElement);
        }
    }
    const paginationContainer = document.querySelector('#pagination-container');
    if (paginationContainer) {
        paginationContainer.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const paginationElement = document.createElement('li');
            paginationElement.classList.add('page-item');
            paginationElement.innerHTML = `
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            `;
            if (i === page) {
                paginationElement.classList.add('active');
            }
            paginationContainer.appendChild(paginationElement);
        }
    }
    const paginationLinks = document.querySelectorAll('#pagination-container a');
    if (paginationLinks) {
        paginationLinks.forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                const newPage = parseInt(link.dataset.page);
                getPosts(newPage, typeSelected);
            });
        });
    }
});
export { initFilter };
