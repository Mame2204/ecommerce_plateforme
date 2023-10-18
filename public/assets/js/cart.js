
import { formatPrice,
    displayCompare,
    addCompareEventListener,
     addFlashMessage,
     fetchData, 
     manageCartLink, 
     addCartEventListenerToLink,
     displayCart,
     updateHeaderCart,
     manageCompareLink} from './library.js';

window.onload = () =>{
    
    console.log("cart");

    let mainContent = document.querySelector('.main_content')

    let cart = JSON.parse(mainContent?.dataset?.cart || false)

    addCartEventListenerToLink()
    
    displayCart(cart)

    updateHeaderCart(cart)
    
    console.log("compare");

    mainContent = document.querySelector('.cart_container')

    let compare = JSON.parse(mainContent?.dataset?.compare || false)

    addCompareEventListener()
    
    displayCompare(compare)

}
