//Switch sections depending on the div clicked
function show_partition(obj)
{
    menu=document.getElementById("partition_menu");
    pictures=document.getElementById("partition_pictures");
    reviews=document.getElementById("partition_reviews");
    contact=document.getElementById("partition_contact");

    switch(obj)
    {
        case "Menu":
        menu.style.display="block";
        pictures.style.display="none";
        reviews.style.display="none";
        contact.style.display="none";
        break;

        case "Pictures":
        menu.style.display="none";
        pictures.style.display="block";
        reviews.style.display="none";
        contact.style.display="none";
        break;

        case "Reviews":
        menu.style.display="none";
        pictures.style.display="none";
        reviews.style.display="block";
        contact.style.display="none";
        break;

        case "Contact":
        menu.style.display="none";
        pictures.style.display="none";
        reviews.style.display="none";
        contact.style.display="block";
        break;
    }
}
