<?php 
if (!isset($tvars['result'])) throw new Exception('No result set on tvars (template vars)');
if (is_array($tvars['result']) && isset($tvars['result']['success']) && isset($tvars['result']['data'])) {
    print(json_encode($tvars['result']));
} else {
    print(json_encode(['success' => true, 'data' => $tvars['result']]));
}
