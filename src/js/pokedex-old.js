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
    axios.post(vars.ajaxurl, params).then((response) => {
        // console.log('Value:', response.data);
        if (pokedex_tag && response.data) {
            pokedex_tag.innerHTML = '<h2>Oldest Pokedex</h2>';
            pokedex_tag.innerHTML += '<p>Version: ' + response.data.version + '</p>';
            pokedex_tag.innerHTML += '<p>Number: ' + response.data.number + '</p>';
        }
    }).catch((error) => {
        console.log('Error:', error);
    });
    // const pokedex_tag = document.getElementById('#old_pokedex');
    // const data = new FormData();
    // data.append( 'action', 'get_pokedex_old' );
    // data.append( 'nonce', vars.nonce );
    // data.append( 'postid', postid);
    // fetch(vars.ajaxurl, {
    //     method: "POST",
    //     credentials: 'same-origin',
    //     body: data
    //   })
    //   .then((response) => response.json())
    //   .then((data) => {
    //     console.log(data);
    //     if (data && pokedex_tag) {
    //         pokedex_tag.innerHTML = '<h2>Oldest Pokedex</h2>';
    //         pokedex_tag.innerHTML += '<p>Version: ' + data.version + '</p>';
    //         pokedex_tag.innerHTML += '<p>Number: ' + data.number + '</p>';
    //     }
    //   })
    //   .catch((error) => {
    //     console.error(error);
    //   });
};
export { getPokedexOld };
