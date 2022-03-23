<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Auth;
use App\Core\Filesystem\Filesystem;
use App\Core\Request;
use App\Core\Storage;

class ProfileController
{
    protected $pageTitle;

    public function index()
    {
        $pageTitle = "Profile";
        $user_id = Auth::user('id');
        $user_data = DB()->select("*", 'users', "id='$user_id'")->get();

        return view('/auth/profile', compact('user_data', 'pageTitle'));
    }

    public function update()
    {
        $request = Request::validate('/profile', [
            'email' => ['required', 'email']
        ]);

        $user_id = Auth::user('id');

        $update_data = [
            'email' => "$request[email]",
            'fullname' => "$request[name]"
        ];

        DB()->update('users', $update_data, "id = '$user_id'");
        redirect("/profile", ["message" => "Profile information updated."]);
    }

    public function changePass()
    {
        $request = Request::validate('/profile', [
            'old-password' => ['required'],
            'new-password' => ['required'],
            'confirm-password' => ['required']
        ]);

        $response_message = Auth::resetPassword($request);
        redirect("/profile", $response_message);
    }

    public function destroy($user_id)
    {
        Request::validate();
        DB()->delete('users', "id = '$user_id'");

        Auth::logout();
    }

    public function uploadAvatar()
    {
        $user_id = Auth::user('id');
        if (Storage::hasFile('upload_file')) {
            $file = Storage::file('upload_file');

            $file_tmp = $file->getTmpFile();
            $file_name = $file->getName();
            $fileSize = $file->getSize();
            $file_type = $file->type();
            $file_extension = $file->getExtension();

            $filename = strtoupper(randChar(4)) . date('Ymdhis') . "." . $file_extension;
            $dir = "public/storage/images/{$user_id}";

            if (!Filesystem::exists($dir . "/" . $filename)) {

                $isStored = $file->storeAs($file_tmp, $dir, $file_type, $filename);

                if ($isStored) {

                    DB()->update("users", [
                        "slug" => "/storage/images/{$user_id}/{$filename}"
                    ], "id = '$user_id'");

                    redirect('/profile', ["message" => "Avatar updated."]);
                } else {

                    redirect('/profile', ["message" => "Avatar updated."]);
                }
            }
        } else {
            redirect('/profile', ["message" => "no file chosen."]);
        }
    }
}
