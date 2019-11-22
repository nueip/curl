<?php
include_once('data.php');

/**
 * Check request method
 *
 * @return string
 */
function getMethod()
{
    if (isset($_SERVER['HTTP_X-Http-Method-Override'])) {
        $result = $_SERVER['HTTP_X-Http-Method-Override'];
    } elseif (isset($_SERVER['REQUEST_METHOD'])) {
        $result = $_SERVER['REQUEST_METHOD'];
    } else {
        $result = 'get';
    }
    return strtoupper($result);
}
switch (getMethod()) {
    case 'GET':
        $result = [
            'code' => 200,
            'message' => 'success',
            // Check have argument or not
            'data' => isset($_GET['id'])
                // list someone data
                ? ($userList[$_GET['id']] ?? [])
                // list all data
                : $userList,
        ];
        break;
    case 'PUT':
        // Get arguments
        $content = file_get_contents('php://input');
        mb_parse_str($content, $data);

        $result = (isset($data['id']) && isset($userList[$data['id']]))
            ? [ 'code' => 200, 'message' => 'success', 'data' => $data + $userList[$data['id']]]
            : [ 'code' => 400, 'message' => 'fail', 'data' => []];
        break;
    default:
        // fail
        $result = [
            'code' => 400,
            'message' => 'Undefined method',
        ];
        break;
}
http_response_code($result['code']);
echo json_encode($result);