<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;
class Posts extends Component
{
    public $posts, $title, $body, $post_id;
    public $updateMode = false;

    public function render()
    {
        $this->posts = Post::all();
        return view('livewire.posts');
    }

    private function resetInputFields(){
        $this->title = '';
        $this->body = '';
    }
    public function store()
    {
        $validatedDate = $this->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        Post::create($validatedDate);

        session()->flash('message', 'Postss Created Successfully.');
        $this->resetInputFields();
        $this->emit('PostsStore');
    }
    public function edit($id)
    {
        $this->updateMode = true;
        $posts = Post::where('id',$id)->first();
        $this->post_id = $id;
        $this->title = $posts->title;
        $this->body = $posts->body;
     }
    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }
    public function update()
    {
        $validatedDate = $this->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        if ($this->post_id) {
            $posts = Post::find($this->post_id);
            $posts->update([
                'title' => $this->title,
                'body' => $this->body,
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Postss Updated Successfully.');
            $this->resetInputFields();
        }
    }
    public function delete($id)
    {
        if($id){
            Post::where('id',$id)->delete();
            session()->flash('message', 'Postss Deleted Successfully.');
        }
    }
}
