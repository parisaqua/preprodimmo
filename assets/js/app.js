/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import Places from 'places.js';
import Map from './modules/map';
import $ from 'jquery';
import 'select2';  
import 'slick-carousel';
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';  
import Inputmask from "inputmask/dist/inputmask";                  

//Numero de telephone
var selector = document.getElementById("telephone");

if (selector !== null) {
var im = new Inputmask("99-99-99-99-99");
im.mask(selector);
 }

//initialisation de la carte
Map.init();

//carousel des images des biens
$('[data-slider]').slick({
  dots: true,
  arrows: true,
  infinite: true,
  speed: 500,
  fade: true,
  autoplay: true,
  autoplaySpeed: 2000,
  cssEase: 'linear'
})

//mise en place de l'autocomplétion des adresses
let inputAddress = document.querySelector('#property_address')
let adminInputAddress = document.querySelector('#admin_property_address')
let profileInputAddress = document.querySelector('#profile_address')
if (inputAddress !== null) {
  let place = Places({
    container: inputAddress
  })
  place.on('change', e => {
    document.querySelector('#property_city').value = e.suggestion.city
    document.querySelector('#property_postalCode').value = e.suggestion.postcode
    document.querySelector('#property_lat').value = e.suggestion.latlng.lat
    document.querySelector('#property_lng').value = e.suggestion.latlng.lng
  })
}
if (adminInputAddress !== null) {
  let place = Places({
    container: adminInputAddress
  })
  place.on('change', e => {
    document.querySelector('#admin_property_city').value = e.suggestion.city
    document.querySelector('#admin_property_postalCode').value = e.suggestion.postcode
    document.querySelector('#admin_property_lat').value = e.suggestion.latlng.lat
    document.querySelector('#admin_property_lng').value = e.suggestion.latlng.lng
  })
}

if (profileInputAddress !== null) {
  let place = Places({
    container: profileInputAddress
  })
  place.on('change', e => {
    document.querySelector('#profile_city').value = e.suggestion.city
    document.querySelector('#profile_postalCode').value = e.suggestion.postcode
    document.querySelector('#profile_lat').value = e.suggestion.latlng.lat
    document.querySelector('#profile_lng').value = e.suggestion.latlng.lng
  })
}

//Mise en place de la recherche
let searchAddress = document.querySelector('#search_address')
if (searchAddress !== null) {
  let place = Places({
    container: searchAddress
  })
  place.on('change', e => {
    document.querySelector('#lat').value = e.suggestion.latlng.lat
    document.querySelector('#lng').value = e.suggestion.latlng.lng
  })
}

// styler les selects de choix multiples avec select 2
$(() => {
  $('select').select2();
});

// formulaire de contact Affichage et Annulation
let $contactButton = $('#contactButton')
$contactButton.click(e => {
  e.preventDefault();
  $('#contactForm').slideDown();
  $('#annulButton').slideDown();
  $contactButton.slideUp();
})
let $annulButton = $ ('#annulButton')
$annulButton.click(e => {
  e.preventDefault();
  $('#contactForm').slideUp();
  $('#contactButton').slideDown();
  
  $annulButton.slideUp();
})


// Suppression des éléments
document.querySelectorAll('[data-delete]').forEach(a => {
  a.addEventListener('click', e => {
    e.preventDefault()
    fetch(a.getAttribute('href'), {
      method: 'DELETE',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({'_token': a.dataset.token})
    }).then(response => response.json())
      .then(data => {
        if (data.success) {
          a.parentNode.parentNode.removeChild(a.parentNode)
        } else {
          alert(data.error)
        }
      })
      .catch(e => alert(e))
  })
})

//Image preview onload inputAddress

var preview = document.getElementById('avatar')
var file_input = document.getElementById('profile_imageFile_file')
window.previewFile  = function ()
{
    let file = file_input.files[0]
    let reader = new FileReader()
    reader.addEventListener('load', function (event)
    {
      preview.src = reader.result
    }, false)

    if (file)
    {
      document.getElementById("currentAvatar").style.display = "none"
      document.getElementById("previewAvatar").style.display = "block"
      reader.readAsDataURL(file)
    }
}


// Formulaire document dans property

// var $collectionHolder;

// setup an "add a tag" link
// var $addDocumentButton = $('<button type="button" class="add_document_link">Ajouter un document</button>');
// var $newLinkLi = $('<div.row></div>').append($addDocumentButton);

// jQuery(document).ready(function() {
//     // Get the ul that holds the collection of tags
//     $collectionHolder = $('div.documents');

//     // add the "add a tag" anchor and li to the tags ul
//     $collectionHolder.append($newLinkLi);

//     // count the current form inputs we have (e.g. 2), use that as the new
//     // index when inserting a new item (e.g. 2)
//     $collectionHolder.data('index', $collectionHolder.find('input').length);

//     $addDocumentButton.on('click', function(e) {
//         // add a new tag form (see next code block)
//         addDocumentForm($collectionHolder, $newLinkLi);
//     });
// });

// function addDocumentForm($collectionHolder, $newLinkLi) {
//   // Get the data-prototype explained earlier
//   var prototype = $collectionHolder.data('prototype');

//   // get the new index
//   var index = $collectionHolder.data('index');

//   var newForm = prototype;
//   // You need this only if you didn't set 'label' => false in your tags field in TaskType
//   // Replace '__name__label__' in the prototype's HTML to
//   // instead be a number based on how many items we have
//   // newForm = newForm.replace(/__name__label__/g, index);

//   // Replace '__name__' in the prototype's HTML to
//   // instead be a number based on how many items we have
//   newForm = newForm.replace(/__name__/g, index);

//   // increase the index with one for the next item
//   $collectionHolder.data('index', index + 1);

//   // Display the form in the page in an li, before the "Add a tag" link li
//   var $newFormLi = $('<div.row></div>').append(newForm);
//   $newLinkLi.before($newFormLi);
// }

// new test documents in property

// setup an "add a tag" link
var $addDocumentLink = $('<button type="button" class="btn btn-info add_document_link">Ajouter un document</button>');
var $newLinkLi = $('<div></div>').append($addDocumentLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
   var $collectionHolder = $('ul.documents');
    
    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);
    
    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    
    $addDocumentLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        
        // add a new tag form (see code block below)
        addDocumentForm($collectionHolder, $newLinkLi);
    });
    
    
});

function addDocumentForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');
    
    // get the new index
    var index = $collectionHolder.data('index');
    
    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);
    
    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);
    
    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li class="doc"></li>').append(newForm);
    
    // also add a remove button, just for this example
    $newFormLi.append('<a href="#" class="btn btn-danger remove-document" title="supprimer"> <i class="fas fa-trash-alt"></i></a>');
    
    $newLinkLi.before($newFormLi);
    
    // handle the removal, just for this example
    $('.remove-document').click(function(e) {
        e.preventDefault();
        
        $(this).parent().remove();
        
        return false;
    });
}


// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

// console.log('Connexion ok, Fred !');
