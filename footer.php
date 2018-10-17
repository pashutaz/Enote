<!-- jQuery first, then Tether, then Bootstrap JS. -->

<script src="/libraries/jquery-3.2.1.min.js"></script>
<!--<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>-->
<script src="/libraries/tether-1.3.3/dist/js/tether.min.js"></script>
<script src="/libraries/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js"></script>

<script type="text/javascript">
    //  меняет формы входа и регистрации
    $(".toggleForms").click(function () {
        $("#signUpForm").toggle();
        $("#logInForm").toggle();
    });

    //    автосохранение
    $("#diary").keyup(function () {

        $.ajax({
            method: "POST",
            url: "updatedatabase.php",
            data: {content: this.value}
        });
//            .done(function(msg) {
//                alert("Data Saved: " + msg);
//            })
//            .error(function(err){
//                console.log(err.statusText)
//            })

    });
    $("#title").keyup(function () {
        $.ajax({
            method: "POST",
            url: "updatedatabase.php",
            data: {title: this.value}
        });
    });

    //Share bar
    Share = {
        vkontakte: function (purl, ptitle, pimg, text) {
            var url = 'http://vkontakte.ru/share.php?';
            url += 'url=' + encodeURIComponent(purl);
            url += '&title=' + encodeURIComponent(ptitle);
            url += '&description=' + encodeURIComponent(text);
            url += '&image=' + encodeURIComponent(pimg);
            url += '&noparse=true';
            Share.popup(url);
        },
        popup: function (url) {
            window.open(url, '', 'toolbar=0,status=0,width=626,height=436');
        }
    };

    //todo: toggle delete button
    $(".noteButton").mouseover(function () {
        var id = $(this)[0].id;
        var buttonId = id.substring(10);
//        document.getElementById("deleteNoteButton"+buttonId).style.visibility = "visible";
        $("#deleteNoteButton" + buttonId).show();
    });
    $(".noteButton").mouseout(function () {
        var id = $(this)[0].id;
        var buttonId = id.substring(10);
//        document.getElementById("deleteNoteButton"+buttonId).style.visibility = "hidden";
        $("#deleteNoteButton" + buttonId).hide();
    });

        $(".deleteNoteButton").hover(function () {
            var id =  $(this)[0].id;
            var buttonId = id.substring(10);
            if ( $("#deleteNoteButton"+buttonId).css("display") === "none"){
                $("#deleteNoteButton"+buttonId).show();
            }else {
                $("#deleteNoteButton"+buttonId).hide();

            }
    //        $("#deleteNoteButton"+buttonId).toggle();
        });

</script>
</body>
</html>
