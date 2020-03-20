<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_added_to_the_library()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' => 'Api sejarah 1',
            'author' => 'Ahmad Mansur'
        ]);

        $book = Book::first();

        $this->assertCount(1, Book::all());

        $response->assertRedirect('/books/' . $book->id);
    }

    /** @test */
    public function a_title_is_required()
    {
        $response = $this->post('/books', [
            'title' => '',
            'author' => 'Dedik',
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_author_is_required()
    {
        $response = $this->post('/books', [
            'title' => 'Api sejarah',
            'author' => '',
        ]);

        $response->assertSessionHasErrors('author');
    }

    /** @test */

    public function a_book_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Api sejarah',
            'author' => 'Ahmad',
        ]);

        $book = Book::first();

        $response = $this->patch('/books/' . $book->id, [
            'title' => 'Aku siapa',
            'author' => 'Dediks'
        ]);

        $this->assertEquals('Aku siapa', Book::first()->title);
        $this->assertEquals('Dediks', Book::first()->author);

        $response->assertRedirect('/books/' . $book->id);
    }

    /** @test */

    public function a_book_can_be_deleted()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Api sejarah',
            'author' => 'Ahmad',
        ]);

        // dd(Book::all());
        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response = $this->delete('/books/' . $book->id);
        $this->assertCount(0, Book::all());

        $response->assertRedirect('/books');
    }
}
