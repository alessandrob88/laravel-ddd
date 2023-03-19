<?php

namespace Tests\Feature;

use App\Events\InvoiceCancel;
use App\Events\InvoiceCreate;
use App\Events\InvoiceRowCreate;
use App\Events\InvoiceRowUpdate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

final class InvoiceControllerTest extends TestCase
{
    /**
     * @dataProvider successfulEventsPayloadProvider
     */
    public function testItDispatchesEventWhenPayloadIsCorrect($payload, $dispatchedEvent)
    {
        Event::fake();

        $response = $this->postJson('/api/invoice', $payload, ['x-auth-token' => env('X_AUTH_TOKEN_VALUE')]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'success' => true,
            'message' => 'Event Accepted',
        ]);

        Event::assertDispatched($dispatchedEvent);
    }

    public function successfulEventsPayloadProvider() {
        return [
           'InvoiceCreate event dispatch' => [
                'payload' => [
                    'status' => 201,
                    'data' => [
                        'event' => 'invoice-create',
                        'payload' => [
                            'customer' => [
                                'id' => 'd999461f-7b61-46c8-9a58-ca871738e816',
                                'businessName' => 'Foo SRL',
                                'vat' => '12345678901',
                            ],
                            'progressive' => 'INV-001',
                            'total' => 0.0,
                            'rows' => [],
                        ],
                    ],
                ],
                'dispatchedEvent' => InvoiceCreate::class,
            ],
           'InvoiceRowCreate event dispatch' => [
                'payload' => [
                    'status' => Response::HTTP_CREATED,
                    'data' => [
                        'event' => 'invoice-row-create',
                        'payload' => [
                            'customer' => [
                                'id' => 'd999461f-7b61-46c8-9a58-ca871738e816',
                                'businessName' => 'Foo SRL',
                                'vat' => '12345678901',
                            ],
                            'progressive' => 'INV-001',
                            'total' => 100.0,
                            'rows' => [
                                [
                                    'id' => '9309999b-4367-4377-9493-c7112dcc5ece',
                                    'description' => 'Lorem ipsum dolor sit amet',
                                    'total' => 100.0,
                                    'quantity' => 1,
                                ],
                            ],
                        ],
                    ],
                ],
                'dispatchedEvent' => InvoiceRowCreate::class,
            ],
           'InvoiceRowUpdate event dispatch' => [
                'payload' => [
                    "status" => 200,
                    "data" => [
                        "event" => "invoice-row-update",
                        "payload" => [
                            "customer" => [
                                "id" => "d999461f-7b61-46c8-9a58-ca871738e816",
                                "businessName" => "Foo SRL",
                                "vat" => "12345678901"
                            ],
                            "progressive" => "INV-001",
                            "total" => 100.00,
                            "rows" => [
                                [
                                    "event" => "update",
                                    "id" => "9309999b-4367-4377-9493-c7112dcc5ece",
                                    "description" => "Lorem ipsum dolor sit amet",
                                    "total" => 50.00,
                                    "quantity" => 1
                                ],
                                [
                                    "event" => "delete",
                                    "id" => "62dff94e-bab4-415b-a6d4-39d46495b833",
                                    "description" => "Lorem ipsum dolor sit amet",
                                    "total" => 50.00,
                                    "quantity" => 1
                                ]
                            ]
                        ]
                    ]
                ],
                'dispatchedEvent' => InvoiceRowUpdate::class,
            ], 
           'InvoiceCancel event dispatch' => [
                'payload' => [
                    'status' => 200,
                    'data' => [
                        'event' => 'invoice-cancel',
                        'payload' => [
                            'customer' => [
                                'id' => 'd999461f-7b61-46c8-9a58-ca871738e816',
                                'businessName' => 'Foo SRL',
                                'vat' => '12345678901',
                            ],
                            'progressive' => 'INV-001',
                            'total' => 100.00,
                            'rows' => [
                                [
                                    'id' => '9309999b-4367-4377-9493-c7112dcc5ece',
                                    'description' => 'Lorem ipsum dolor sit amet',
                                    'total' => 50.00,
                                    'quantity' => 1,
                                ],
                                [
                                    'id' => '62dff94e-bab4-415b-a6d4-39d46495b833',
                                    'description' => 'Lorem ipsum dolor sit amet',
                                    'total' => 50.00,
                                    'quantity' => 1,
                                ],
                            ]
                        ],
                    ],
                ],
                'dispatchedEvent' => InvoiceCancel::class,
            ],
        ];
    }

    /**
     * @dataProvider unSuccessfulEventsPayloadProvider
     */
    public function testItFailsToDispatchWithNotValidPayload($payload, $errorMessage) 
    {
        Event::fake();

        $response = $this->postJson('/api/invoice', $payload, ['x-auth-token' => env('X_AUTH_TOKEN_VALUE')]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'success' => false,
            'message' => $errorMessage,
        ]);

    }

    public function unSuccessfulEventsPayloadProvider() {
        return [
            'InvoiceCreate event with wrong payload' => [
                'payload' => [
                    'status' => 201,
                    'data' => [
                        'event' => 'invoice-create',
                        'payload' => [
                            'customer' => [
                                'businessName' => 'Foo SRL',
                                'vat' => '12345678901',
                            ],
                            'progressive' => 'INV-001',
                            'total' => 0.0,
                            'rows' => [],
                        ],
                    ],
                ],
                'errorMessage' => [
                    'data.payload.customer.id' => [
                        0 => 'The data.payload.customer.id field is required.',
                    ],
                ],
            ],
            'InvoiceRowCreate event with wrong payload' => [
                'payload' => [
                    'status' => 201,
                    'data' => [
                        'event' => 'invoice-row-create',
                        'payload' => [
                            'customer' => [
                                'id' => 'd999461f-7b61-46c8-9a58-ca871738e816',
                                'vat' => '12345678901',
                            ],
                            'progressive' => 'INV-001',
                            'total' => 100.0,
                            'rows' => [
                                [
                                    'id' => '9309999b-4367-4377-9493-c7112dcc5ece',
                                    'description' => 'Lorem ipsum dolor sit amet',
                                    'total' => 100.0,
                                    'quantity' => 1,
                                ],
                            ],
                        ],
                    ],
                ],
                'errorMessage' => [
                    'data.payload.customer.businessName' => [
                        0 => 'The data.payload.customer.business name field is required.',
                    ],
                ],
            ],
            'InvoiceRowUpdate event with wrong payload' => [
                'payload' => [
                    "status" => 200,
                    "data" => [
                        "event" => "invoice-row-update",
                        "payload" => [
                            "customer" => [
                                "id" => "d999461f-7b61-46c8-9a58-ca871738e816",
                                "businessName" => "Foo SRL",
                            ],
                            "progressive" => "INV-001",
                            "total" => 100.00,
                            "rows" => [
                                [
                                    "event" => "update",
                                    "id" => "9309999b-4367-4377-9493-c7112dcc5ece",
                                    "description" => "Lorem ipsum dolor sit amet",
                                    "quantity" => 1
                                ],
                                [
                                    "event" => "delete",
                                    "description" => "Lorem ipsum dolor sit amet",
                                    "total" => 50.00,
                                    "quantity" => 1
                                ]
                            ]
                        ]
                    ]
                ],
                'errorMessage' => [
                    'data.payload.customer.vat' => [
                        0 => 'The data.payload.customer.vat field is required.',
                    ],
                    'data.payload.rows.1.id' => [
                        0 => 'The data.payload.rows.1.id field is required.',
                    ],
                    'data.payload.rows.0.total' => [
                        0 => 'The data.payload.rows.0.total field is required.',
                    ],
                ],
            ], 
            'InvoiceCancel event with wrong payload' => [
                'payload' => [
                    'status' => 200,
                    'data' => [
                        'event' => 'invoice-row-update',
                        'payload' => [
                            'customer' => [
                                'id' => 'd999461f-7b61-46c8-9a58-ca871738e816',
                                'businessName' => 'Foo SRL',
                                'vat' => '12345678901',
                            ],
                            'progressive' => 'INV-001',
                            'total' => 'foo',
                            'rows' => [
                                [
                                    'id' => '9309999b-4367-4377-9493-c7112dcc5ece',
                                    'description' => 'Lorem ipsum dolor sit amet',
                                    'total' => 50.00,
                                    'quantity' => 1,
                                ],
                                [
                                    'id' => '62dff94e-bab4-415b-a6d4-39d46495b833',
                                    'description' => 'Lorem ipsum dolor sit amet',
                                    'total' => 50.00,
                                    'quantity' => 1,
                                ],
                            ]
                        ],
                    ],
                ],
                'errorMessage' => [
                    'data.payload.total' => [
                        0 => 'The data.payload.total field must be a number.'
                    ]
                ]
            ],
            'InvoiceCreate event with two invoice rows with same id' => [
                'payload' => [
                    'status' => 201,
                    'data' => [
                        'event' => 'invoice-create',
                        'payload' => [
                            'customer' => [
                                'businessName' => 'Foo SRL',
                                'vat' => '12345678901',
                            ],
                            'progressive' => 'INV-001',
                            'total' => 0.0,
                            'rows' => [
                                [
                                    'id' => '9309999b-4367-4377-9493-c7112dcc5ece',
                                    'description' => 'Lorem ipsum dolor sit amet',
                                    'total' => 100.00,
                                    'quantity' => 1,
                                ],
                                [
                                    'id' => '9309999b-4367-4377-9493-c7112dcc5ece',
                                    'description' => 'Lorem ipsum dolor sit amet',
                                    'total' => 100.00,
                                    'quantity' => 1,
                                ],
                            ],
                        ],
                    ],
                ],
                'errorMessage' => [
                    'data.payload.rows.1.id' => [
                        0 => 'The data.payload.rows.1.id value must be unique in payload',
                    ],
                ],
            ],
            'InvoiceRowUpdate event with wrong row events' => [
                'payload' => [
                    "status" => 200,
                    "data" => [
                        "event" => "invoice-row-update",
                        "payload" => [
                            "customer" => [
                                "id" => "d999461f-7b61-46c8-9a58-ca871738e816",
                                "businessName" => "Foo SRL",
                                "vat" => '12345678901',
                            ],
                            "progressive" => "INV-001",
                            "total" => 100.00,
                            "rows" => [
                                [
                                    "event" => "pippo",
                                    "id" => "9309999b-4367-4377-9493-c7112dcc5ece",
                                    "description" => "Lorem ipsum dolor sit amet",
                                    'total' => 100.00,
                                    "quantity" => 1
                                ],
                                [
                                    'id' => '8309999b-4367-4377-9493-c7112dcc5ece',
                                    "description" => "Lorem ipsum dolor sit amet",
                                    "total" => 50.00,
                                    "quantity" => 1
                                ]
                            ]
                        ]
                    ]
                ],
                'errorMessage' => [
                    'data.payload.rows.0.event' => [
                        0 => 'The selected data.payload.rows.0.event is invalid.',
                    ],
                    'data.payload.rows.1.event' => [
                        0 => 'The data.payload.rows.1.event field is required when data.event is invoice-row-update.',
                    ],
                ],
            ], 
        ];
    }

    public function testItReturnsUnauthorizedResponseIfTokenIsNotSet()
    {
        $response = $this->postJson('/api/invoice', [], []);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'success' => false,
            'message' => Response::$statusTexts[Response::HTTP_UNAUTHORIZED],
        ]);
    }

    public function testItReturnsBadRequestResponseIfTokenIsNotValid()
    {
        $response = $this->postJson('/api/invoice', [], ['x-auth-token' => 'foo']);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'success' => false,
            'message' => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
        ]);
    }
}
