<?php

class PostsTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('posts')->delete();

        for ($i=1; $i<31; $i++) {
            Post::create(array(
                'title' => "Post $i",
                'body' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere, obcaecati, alias, sint, doloremque amet cupiditate explicabo nam ipsa perferendis suscipit voluptatum soluta eveniet aut doloribus culpa quia molestiae reprehenderit delectus.'
            ));
        }
    }

}