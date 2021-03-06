<?php

use Mockery as m;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Collect\Action\SyncAction;

class SyncActionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_sync()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $request = m::spy('Payum\Core\Request\Sync');
        $gateway = m::spy('Payum\Core\GatewayInterface');
        $details = new ArrayObject([
            'cust_order_no' => 'foo.cust_order_no',
            'order_amount' => 'foo.order_amount',
            'refund_amount' => 'foo.refund_amount',
        ]);

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getModel')->andReturn($details);

        $action = new SyncAction();
        $action->setGateway($gateway);
        $action->execute($request);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $request->shouldHaveReceived('getModel')->twice();
        $gateway->shouldHaveReceived('execute')->with(m::type('PayumTW\Collect\Request\Api\GetTransactionData'))->once();
    }
}
