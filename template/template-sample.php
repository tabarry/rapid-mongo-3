<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include('inc-head.php'); ?>
        <?php include('inc-head-date.php'); ?>
        <script type="text/javascript">
            $(document).ready(function(){
                riEqualCols('menu-area','content-area');
                if($('#riForm')){
                    $('#riForm').submit(function(){
                        $.post(document.riForm.action, $(this).serialize(), function(data){
                            $('#error-area ul').html(data);
                        });				
                        return false;
                    });                
                }
            
            });          
        </script>   

    </head>
    <body>
        <div id="wrapper">
            <!--HEADER STARTS-->
            <?php include('inc-header.php'); ?>
            <!--CONTENT STARTS-->
            <div id="content">
                <!--MENU STARTS-->
                <?php include('inc-menu.php'); ?>
                <!--CONTENT AREA-->
                <div id="content-area">
                    <div id="error-area">
                        <ul></ul>
                    </div>    
                    <div id="message-area">
                        <p></p>
                    </div>
                    <!--RI STARTS-->
                    [RAPID-CODE]
                    <!--RI ENDS-->
                </div>
            </div>
            <!--FOOTER-->
            <?php include('inc-footer.php'); ?> 
        </div>        

    </body>
</html>