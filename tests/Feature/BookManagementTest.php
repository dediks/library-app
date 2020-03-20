<?php

namespace Tests\Feature;

use App\Author;
use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    private $data = [
        'title' => 'Api sejarah 1',
        'author_id' => 'Ahmad Mansur'
    ];

    /** @test */
    public function a_book_can_be_added_to_the_library()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/books', $this->data);

        $book = Book::first();

        $this->assertCount(1, Book::all());

        $response->assertRedirect('/books/' . $book->id);
    }

    /** @test */
    public function a_title_is_required()
    {
        $response = $this->post('/books', array_merge($this->data, [
            'title' => '',
        ]));

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_author_is_required()
    {
        $response = $this->post('/books', array_merge($this->data, [
            'author_id' => '',
        ]));

        $response->assertSessionHasErrors('author_id');
    }

    /** @test */

    public function a_book_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', $this->data);

        $book = Book::first();

        $response = $this->patch('/books/' . $book->id, [
            'title' => 'Aku siapa',
            'author_id' => 1
        ]);

        $this->assertEquals('Aku siapa', Book::first()->title);
        $this->assertEquals(2, Book::first()->author_id);

        $response->assertRedirect('/books/' . $book->id);
    }

    /** @test */

    public function a_book_can_be_deleted()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', $this->data);

        // dd(Book::all());
        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response = $this->delete('/books/' . $book->id);
        $this->assertCount(0, Book::all());

        $response->assertRedirect('/books');
    }

    /** @test */

    public function a_new_author_is_automatically_added()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Api Sejarah 2',
            'author_id' => 'Dedik'
        ]);

        $author = Author::first();

        // dd($author);
        $book = Book::first();

        $this->assertCount(1, Book::all());
        $this->assertCount(1, Author::all());
        $this->assertEquals($author->id, $book->author_id);
    }
}
