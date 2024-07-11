<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimulationTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    private function getHeader(): array
    {
        $data = [
            'email' => 'administrator@test.com.br',
            'password' => 'Nil#123@$'
        ];

        $response = $this->postJson(
            route('auth.login-custom'),
            $data
        );

        return [
            'Authorization' => 'Bearer ' . $response->json()['data']['token']
        ];
    }

    public function test_credit()
    {
        $header = $this->getHeader();

        $response = $this->postJson(
            route('simulation.credit'),
            [
                'cpf' => '11111111111'
            ],
            $header
        );

        $this->assertTrue($response->json()['success']);
    }

    public function test_offer()
    {
        $header = $this->getHeader();

        $response = $this->postJson(
            route('simulation.offer'),
            [
                'cpf' => '11111111111',
                "instituicao_id" => 1,
                "codModalidade" => "13"
            ],
            $header
        );

        $this->assertTrue($response->json()['success']);
    }

    public function test_simulation()
    {
        $header = $this->getHeader();

        $response = $this->postJson(
            route('simulation.calculate'),
            [
                'cpf' => '11111111111',
                "valorSolicitado" => 5000,
                "qntParcelas" => 48
            ],
            $header
        );

        $this->assertTrue($response->json()['success']);
    }
}
