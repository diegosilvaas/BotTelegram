<?php



require 'vendor/autoload.php';

use TelegramBot\Api\Client;

$botToken = '6164555240:AAFWIrIjjBXeGIT-QDKCFnkHCc1t4uklJEY';
$webhookUrl = 'https://4203-189-90-235-95.ngrok-free.app';

$telegram = new Client($botToken);
$telegram->setWebhook($webhookUrl);

$cardapio = "Nosso cardápio:\n\n [ 1 ] Burguer caseiro: R$ 30\n [ 2 ] X Salada: R$ 20\n [ 3 ] X Frango: R$ 25\n [ 4 ] X Calabresa: R$ 28\n\nDigite /pedido seguido do número da pizza desejada.";
//linha adicionada
$bebidas = "Nossas bebidas:\n\n [ 1 ] Coca 2 lts: R$ 15\n [ 2 ] Coca 1,5 lts: R$ 10\n [ 3 ] Coca 500 ml: R$ 8\n [ 4 ] Coca lata: R$ 5\n\nDigite /bebida seguido do número da bebida desejada.";

$telegram->command('start', function ($message) use ($telegram, $cardapio) {
    $telegram->sendMessage($message->getChat()->getId(), 'Bem-vindo ao bot de atendimento de pedidos! Para ver o nosso cardápio, digite /cardapio.');
});


$telegram->command('cardapio', function ($message) use ($telegram, $cardapio) {
    $telegram->sendMessage($message->getChat()->getId(), $cardapio);
});

// linha adicionada
$telegram->command('bebida', function ($message) use ($telegram, $bebida) {
    $telegram->sendMessage($message->getChat()->getId(), $bebida);
});




///update
$telegram->on(function ($update) use ($telegram) {
    $message = $update->getMessage();
    $chatId = $message->getChat()->getId();
    $text = $message->getText();

    if (strpos($text, '/pedido') === 0) {
        $pedido = substr($text, 8);
        $pedidoArquivo = "Pedido: " . $pedido . "\n";
        file_put_contents('pedidos.txt', $pedidoArquivo, FILE_APPEND);

        switch ($pedido) {
            case '1':
                $telegram->sendMessage($chatId, 'Você escolheu o Burguer caseiro. Digite /endereco para informar o endereço de entrega.');
                break;
            case '2':
                $telegram->sendMessage($chatId, 'Você escolheu o X Salada. Digite /endereco para informar o endereço de entrega.');
                break;
            case '3':
                $telegram->sendMessage($chatId, 'Você escolheu o X Frango. Digite /endereco para informar o endereço de entrega.');
                break;
            case '4':
                $telegram->sendMessage($chatId, 'Você escolheu o X Calabresa. Digite /endereco para informar o endereço de entrega.');
                break;
            default:
                $telegram->sendMessage($chatId, 'Opção inválida. Por favor, digite /pedido seguido do número da pizza desejada.');
                break;
        }
    } elseif (strpos($text, '/endereco') === 0) {
        $endereco = substr($text, 10);
        $enderecoArquivo = "Endereço de entrega: " . $endereco . "\n";
        file_put_contents('pedidos.txt', $enderecoArquivo, FILE_APPEND);
        $telegram->sendMessage($chatId, 'Seu pedido foi recebido e será entregue em breve. Obrigado!');
    } 

}, function ($update) {
    return true;
});


$telegram->run();
