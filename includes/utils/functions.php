<?php
function alertBox($message, $ref = '#', $btnTxt = 'Close'){
    return  <<<HTML
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ALERT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                {$message}
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.href='{$ref}'">
                {$btnTxt}
                </button>
                </div>
            </div>
            </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#myModal").modal("show");
                });
            </script>
            HTML;
            
}

function success($message=''){
    return <<<HTML
            <script>
            $(function () {
                $.notify({
                    title: '[SUCCESS]',
                    message: '{$message}'
                },
                {   
                    type: 'success',
                    newest_on_top: true
                }
                )
            });
            </script>
            HTML;
}

function error($message=''){
    return <<<HTML
            <script>
            $(function () {
                $.notify({
                    title: '[ERROR]',
                    message: '{$message}'
                },
                {   
                    type: 'danger',
                    newest_on_top: true
                }
                )
            });
            </script>
            HTML;
}

function getStudentName($student_id){
    $client = new \GuzzleHttp\Client([
        'base_uri' => 'http://reg.siit.tu.ac.th/',
        'timeout'  => 5.0,
    ]);
    
    $r = $client->request('POST', 'http://reg.siit.tu.ac.th/registrar/learn_time.asp', [
        'form_params' => [
                'f_studentcode' => $student_id,
                'f_cmd' => 1
            ]
                
    ]);
    
    $body =  (string) $r->getBody();
    $dom = \voku\helper\HtmlDomParser::str_get_html($body);
    
    $s = $dom->find('td[width=250]', 0)->innertext;
    $result = explode('<br', $s)[0];

    if (strlen($result) > 0 && strlen(trim($result)) == 0 or $result == null) $result = null;

    return $result;
}

function checkDictwithPOST(&$dict, &$msgBox){
    foreach ($dict as $key => $value) {
        if (!isset($_POST[$key]) || trim($_POST[$key]) == '') {
            $msgBox = error($key . ' error');
            break;
        } else {
            $dict[$key] = trim($_POST[$key]);
        }
    }
}
?>
