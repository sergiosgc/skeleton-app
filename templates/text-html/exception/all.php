<?php header('Content-type: text/plain'); ?>

 /$$   /$$           /$$                                 /$$ /$$                 /$$       /$$$$$$$$                                           /$$     /$$                    
| $$  | $$          | $$                                | $$| $$                | $$      | $$_____/                                          | $$    |__/                    
| $$  | $$ /$$$$$$$ | $$$$$$$   /$$$$$$  /$$$$$$$   /$$$$$$$| $$  /$$$$$$   /$$$$$$$      | $$       /$$   /$$  /$$$$$$$  /$$$$$$   /$$$$$$  /$$$$$$   /$$  /$$$$$$  /$$$$$$$ 
| $$  | $$| $$__  $$| $$__  $$ |____  $$| $$__  $$ /$$__  $$| $$ /$$__  $$ /$$__  $$      | $$$$$   |  $$ /$$/ /$$_____/ /$$__  $$ /$$__  $$|_  $$_/  | $$ /$$__  $$| $$__  $$
| $$  | $$| $$  \ $$| $$  \ $$  /$$$$$$$| $$  \ $$| $$  | $$| $$| $$$$$$$$| $$  | $$      | $$__/    \  $$$$/ | $$      | $$$$$$$$| $$  \ $$  | $$    | $$| $$  \ $$| $$  \ $$
| $$  | $$| $$  | $$| $$  | $$ /$$__  $$| $$  | $$| $$  | $$| $$| $$_____/| $$  | $$      | $$        >$$  $$ | $$      | $$_____/| $$  | $$  | $$ /$$| $$| $$  | $$| $$  | $$
|  $$$$$$/| $$  | $$| $$  | $$|  $$$$$$$| $$  | $$|  $$$$$$$| $$|  $$$$$$$|  $$$$$$$      | $$$$$$$$ /$$/\  $$|  $$$$$$$|  $$$$$$$| $$$$$$$/  |  $$$$/| $$|  $$$$$$/| $$  | $$
\______/ |__/  |__/|__/  |__/ \_______/|__/  |__/ \_______/|__/ \_______/ \_______/      |________/|__/  \__/ \_______/ \_______/| $$____/    \___/  |__/ \______/ |__/  |__/
                                                                                                                                 | $$                                        
                                                                                                                                 | $$                                        
                                                                                                                                 |__/                                        
Top level exception received 
==============================

Message: <?= $tvars['exception']->getMessage() ?>


Exception Class: \<?= get_class($tvars['exception']) ?>


Code: <?= $tvars['exception']->getCode() ?>


Backtrace: 

<?php
       $widths = array(4, 8, 7);
       foreach ($tvars['exception']->getTrace() as $idx => $tb) { 
           $widths[0] = max($widths[0], strlen((string)$idx));
           $widths[1] = max($widths[1], strlen(isset($tb['class']) ? sprintf('%s%s%s', $tb['class'], $tb['type'], $tb['function']) : $tb['function']));
           $widths[2] = max($widths[2], strlen($tb['file'] . ' +' . $tb['line']));
       }
       $lineFormatString = sprintf('  %%-%ds | %%-%ds | %%-%ds' . "\n", $widths[0], $widths[1], $widths[2]);
       printf($lineFormatString, ' ', 'Function', 'Location');
       echo(strtr(sprintf($lineFormatString, '', '', ''), array('|' => '+', ' ' => '-', "\n" => "-\n")));
       if (0 == count($tvars['exception']->getTrace())) printf($lineFormatString, 0, ' ', $tvars['exception']->getFile() . ' +' . $tvars['exception']->getLine());
       foreach ($tvars['exception']->getTrace() as $idx => $tb) { 
           printf($lineFormatString, $idx, isset($tb['class']) ? sprintf('%s%s%s', $tb['class'], $tb['type'], $tb['function']) : $tb['function'], $idx == 0 ? ($tvars['exception']->getFile() . ' +' . $tvars['exception']->getLine()) : ($tb['file'] . ' +' . $tb['line']));
       }