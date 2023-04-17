# Fever - Wordpress Challenge

Child theme development based on [Understrap Child starter](https://docs.understrap.com/#/understrap-child/).

## Installation

- Install a fresh copy of [Wordpress](https://wordpress.org/download/).
- It's assumed that you have installed the [Understrap parent theme](https://understrap.com/).
- [Advanced Custom Fields](https://www.advancedcustomfields.com/) plugin is required in this development to manage custom meta fields. Please install and activate it.

- Install dependencies in child theme (understrap-child/). This may take some time:

```bash
npm install
```

```bash
composer install
```
- Activate the child theme.

## Usage

- Random pokemon: create a page and assign the template called 'Random Pokemon'.
- Generate pokemon: create a page and assign the template called 'Generate Pokemon'.
- Filter: create a page and assign the template called 'Filter'.
- Tests available in understrap-child/tests/.

## Endpoints
- List stored: wp-json/pokemon/v1/list
- Get data: wp-json/pokemon/v1/get/{id}

## Notes about development
### Pokedex
I never saw Pokemon, so there was some kind of information that I wasn't sure if it was correct. For example, to get the Pokedex version number and name (old and recent) I made a request to /pokemon and got the values of the 'game_indices' attribute.

Then, I assumed that the information in index 0 is the oldest one, so for me that is the version number. To get the name of that version I made another request to the url of the 'url' attribute of the version in that index and got its name.

For the recent versions I did the same process, but instead of getting the information from index 0 I assumed that the most recent version is in the last index of 'game_indices'.

### Taxonomies
When creating a pokemon manually or automatically, the Type (primary and secondary), Attacks and Version information is stored in taxonomies. There is one created for each. 

I did this because all this information can be shared between several pokemon.
With ACF I made custom selection fields to select the primary and secondary type. It could be made simpler by using the Yoast plugin that allows you to select primary terms in custom taxonomies. I did the same for the selection of Pokedex versions (old and recent).

If you change one of these values in the ACF fields, after saving the post, it links the selected values with the terms to maintain the relationship between post and terms.

### Generator
When executing this functionality, first we check if the pokemon already exists in the database by performing a query to obtain a post with the same slug (for this we use the name of the Pokemon obtained in the API request).

If the post already exists its information and taxonomies (Types, Versions and Attacks) will be updated. Otherwise a new post will be created with all the data.

In addition, in each request the terms of each taxonomy are inserted or updated (if they already exist) with the information of the Pokemon obtained, and then linked to the post.

## Answers about DAPI and Traffic
### DAPI implementation
It would be possible, but a new Class would have to be added with all the requests to this API. It could be also modify the existing API class to adapt it, but I think it would be better to have it separately.

It would also be necessary to create a new CPT to store the Digimon, or to create a taxonomy in the Pokemon CPT to differentiate them.

### Traffic
I would use the Wordpress cache functions to store the API request responses. This way it would be faster and would not consume so many DB resources since the information would be cached. It would be necessary to delete this cache from time to time to show the updated API information.