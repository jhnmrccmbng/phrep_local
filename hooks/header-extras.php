<style>
    @media screen and (max-width: 600px) {
                #login_splash{
                    background-image: none;
                    position: relative;
                    height: 300px;
                }
                #last-about{
                    padding-bottom: 100px;
                }
                #nn_1, #nn_2, #nt_4, #nt_5{
                    display: none;
                }
                #allrights{
/*                    display: block;
                    margin-top: 5px;*/
                    font-size: 8px;
                }
                #phrep{
                    display: none;
                }
                #phrepsmall{
                    display: block;
                    font-weight: 100;
                    font-size: 30px;
                }
                .portal{
                    display: block;
                    color: #ffffff;
                    padding-top: 5px;
                    padding-left: 5px;
                    text-align: center;
                    text-decoration: none;
                }
                #toplogin{
                    display: none;
                }
                #textland{          
                    color: #ffffff;
                    padding-top: 0px;
                }
            }
</style>

<script>    
        
var nb = document.getElementsByClassName('navbar-nav');
    for (var i = 0; i < nb.length; i++) {
        if(nb[i].tagName === 'UL'){
        nb[i].id = "nb_" + [i + 1];        
        }
    } 

    document.getElementById("nb_1").remove();
        
</script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="contact">Contact us</h1>
            </div>
            <div class="modal-body">
                <p>For your queries and concerns, you may get in touch with us at: </p><br>
                <address>
                <p>Address: </p>
                    <strong>Philippine Health Research Ethics Board c/o 
                        Philippine Council for Health Research and Development, 
                        Imelda Building, Gen. Santos Ave., Bicutan, Taguig City </strong><br>
                    <abbr title="Phone">Telephone:</abbr> (02) 837-7537 <br>
                    <abbr title="Phone">Telephone:</abbr> (02) 837-2071 to 82 loc 2112 <br>
                    <abbr title="Fax">Fax:</abbr> (02) 837-2924  <br><br>
                </address>

                <address>
                <address>
                <p>Contact Persons:</p>
                <strong>Ms. Marie Jeanne B. Berroya, Ms. Daphne Joyce Maza, and Ms. Marielle Louise Custodio</strong><br>
                <abbr title="Email">Email:</abbr> nec@pchrd.dost.gov.ph <br><br>
        
                </address>
                </address>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>
