// import axios from 'axios';
const getPokedexOld = () => {
    const oldPokedex = document.querySelector('#show_old_pokedex');
    if (oldPokedex) {
        const postid = oldPokedex.dataset.postid;
        oldPokedex.addEventListener('click', function () {
            clickButton(postid);
        }, false);
    }
};
const clickButton = (postid = '') => {
    const params = new URLSearchParams();
    params.append('action', 'get_pokedex_old');
    params.append('post_id', postid);
    const pokedex_tag = document.getElementById('old_pokedex');
    axios.post(ajaxurl.ajaxurl, params).then((response) => {
        // console.log('Value:', response.data);
        if (pokedex_tag && response.data) {
            pokedex_tag.innerHTML = '<h2>Oldest Pokedex</h2>';
            pokedex_tag.innerHTML += '<p>Version: ' + response.data.version + '</p>';
            pokedex_tag.innerHTML += '<p>Number: ' + response.data.number + '</p>';
        }
    }).catch((error) => {
        console.log('Error:', error);
    });
};
export { getPokedexOld };
