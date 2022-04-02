<div class="row">
    <div class="list-group">
        <a href="sec_dashboard_active.php" id="" class="list-group-item">Submissions</a>
        <a href="" class="list-group-item" id="idp">My Profile</a>
        <a href="sec_register_reviewer.php" id="rev" class="list-group-item">Reviewers</a>
        <a href="sec_suggestion.php" id="sugg" class="list-group-item">Suggestions</a>
        <a href="sec_search_information.php" id="search" class="list-group-item">Search</a>
<!--        <a href="#" class="list-group-item">Messages</a>
        <a href="#" class="list-group-item">Documents</a>
        <a href="#" class="list-group-item">Certificates</a>-->
    </div>
</div>



<script>
var urlink = window.location.href;
var url_split = urlink.split("/");
var num = url_split.length-1;

var qsplit = url_split[num].split("?");
qsplit[0];

if(qsplit[0] === 'sec_dashboard_active.php'){
    
    var elem = document.getElementById("subm");
    elem.classList.add("active");
}
else if(qsplit[0] === 'sec_personal_info.php'){
    
    var elem = document.getElementById("idp");
    elem.classList.add("active");
}
else if(qsplit[0] === 'sec_register_reviewer.php'){
        
    var elem = document.getElementById("rev");
    elem.classList.add("active");
}
else if(qsplit[0] === 'sec_suggestion.php'){
        
    var elem = document.getElementById("sugg");
    elem.classList.add("active");
}
else if(qsplit[0] === 'sec_search_information.php'){
        
    var elem = document.getElementById("search");
    elem.classList.add("active");
}


</script>