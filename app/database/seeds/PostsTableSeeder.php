<?php

class PostsTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('posts')->delete();

        Post::create(array(
            'title' => 'Post 1',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere, obcaecati, alias, sint, doloremque amet cupiditate explicabo nam ipsa perferendis suscipit voluptatum soluta eveniet aut doloribus culpa quia molestiae reprehenderit delectus.'
        ));

        Post::create(array(
            'title' => 'Post 2',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere, obcaecati, alias, sint, doloremque amet cupiditate explicabo nam ipsa perferendis suscipit voluptatum soluta eveniet aut doloribus culpa quia molestiae reprehenderit delectus.'
        ));

        Post::create(array(
            'title' => 'Post 3',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere, obcaecati, alias, sint, doloremque amet cupiditate explicabo nam ipsa perferendis suscipit voluptatum soluta eveniet aut doloribus culpa quia molestiae reprehenderit delectus.'
        ));
    }

}