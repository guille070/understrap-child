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

## Answers
### DAPI implementation
It would be possible, but a new Class would have to be added with all the requests to this API. It could be also modify the existing API class to adapt it, but I think it would be better to have it separately.

It would also be necessary to create a new CPT to store the Digimon, or to create a taxonomy in the Pokemon CPT to differentiate them.

### Traffic
I would use the Wordpress cache functions to store the API request responses. This way it would be faster and would not consume so many DB resources since the information is stored in cache.