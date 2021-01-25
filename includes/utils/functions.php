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

function sendEmail($data) {
    require_once 'config/mailconf.php';

    $data['subject'] = '[Event #' . $data['event_id'] . ']: ' . $data['event_name'] . ' (PENDING)';

    $data['content'] = 'Event has been locked. Please read the below to settle the event costs. <br><br>';
    if ($data['owe_amount'] > 0) {
        $data['content'] .= 'You owe ' . $data['creator_name'] . ' <b>' . $data['owe_amount'] . '</b>';
    } else {
        $data['content'] .= $data['creator_name'] . ' owes you <b>' . $data['owe_amount']*(-1) . '</b>.';
    }

    $data['content'] .= 'Bank name: ' . $data['creator_bank_name'] . '<br>';
    $data['content'] .= 'Bank number: ' . $data['creator_bank_number'] . '<br>';

    $email = new \SendGrid\Mail\Mail(); 
    $email->setFrom($mail_from, "TransferAgain");
    $email->setSubject($data['subject']);
    $email->addTo($data['student_id'] . '@g.siit.tu.ac.th', $data['display_name']);
    $email->addContent(
        'text/html', $data['content'] . '<br><br>This is an automated message. Please do not reply.'
    );
    $sendgrid = new \SendGrid($sendgrid_api_key);
    try {
        $response = $sendgrid->send($email);
        // print $response->statusCode() . "\n";
        // print_r($response->headers());
        // print $response->body() . "\n";
    } catch (Exception $e) {
        // echo 'Caught exception: '. $e->getMessage() ."\n";
        $response = 0;
    }

    return $response;
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

function editButton($data)
{
    $data = json_encode($data); //Must use single brace for data-service container!
    return      <<<HTML
                <button type="button" class="btn" data-toggle="modal" data-target="#EditModal" data-type="edit" data-service='{$data}'> 
                <span style="color: Purple;">
                <i class="fas fa-edit fa-2x"></i>
                </span>
                </button>
                HTML;
}

function deleteButton($data)
{
    $data = json_encode($data); //Must use single brace for data-service container!
    return      <<<HTML
                <button type="button" class="btn" data-toggle="modal" data-target="#DeleteModal" data-type="delete" data-service='{$data}'> 
                <span style="color: DarkRed;">
                <i class="fas fa-trash fa-2x"></i>
                </span>
                </button>
                HTML;
}
?>
