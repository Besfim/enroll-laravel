<?php

namespace App\Http\Controllers;

use App\Application;
use App\Association;
use App\Department;
use App\Operation;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function home()
    {
        return redirect()->route('association',[8]);
//        $association = Association::all();
//        return view('user.home',['association' => $association]);
    }
    public function login(Request $request)
    {
        if($request->isMethod('get'))
        {
            if(Session::has('user'))
                return redirect('/');
            else
                return view('user.log');
        }
        else if($request->isMethod('post'))
        {
            $user = Users::where($request->input('user'))->first();
            if($user)
            {
                Session::put('user',$user->id);
                $log2url = Session::get('log2url');
                Session::forget('log2url');
                return redirect($log2url ?: '/')->cookie('userPhone',$request->input('user')['phone'],1440)->cookie('userPassword',$request->input('user')['password'],1440);
            }
            else
            {
                Session::forget('user');
                return redirect()->back()->withInput($request->input('user'))->with('msg','用户密码错误')->cookie('userPhone',null,0)->cookie('userPassword',null,0);
            }
        }
    } //登录
    public function logout(Request $request)
    {
        $request->session()->forget('user');
        return redirect('userLog')->cookie('userPhone',null,0)->cookie('userPassword',null,0);
    } //登出
    public function register(Request $request)
    {
        if($request->isMethod('get'))
        {

            return view('user.register');
        }
        else if($request->isMethod('post'))
        {
            $this->validate($request,[
               'user.phone' => 'required|regex:/^1[34578][0-9]{9}$/|unique:user',
                'user.password' => 'required|min:4|max:20',
                'user.name' => 'required|min:2|max:4',
                'user.gender' => 'required|integer',
                'user.birth' => 'required|date',
                'user.school' => 'required',
                'user.major' => 'required',
                'user.class' => 'required|integer',
            ]);
            $SMScode = $request->input('SMScode');
            $phone = $request->input('user.phone');
            if($SMScode != Session::get('SMScode') || $phone != Session::get('captchaPhone'))
                return redirect()->back()->withInput($request->input())->with('msg','手机验证码错误');
            $user = Users::create($request->input('user'));
            Session::put('user',$user->id);
            $log2url = Session::get('log2url');
            return redirect($log2url ?: '/')->cookie('userPhone',$request->input('user.phone'),1440)->cookie('userPassword',$request->input('user.password'),1440)->with('msg','注册成功！');
        }
    } //注册
    public function resetPassword(Request $request)
    {
        if($request->isMethod('get'))
            return view('user.resetPassword');
        else if($request->isMethod('post'))
        {
            $this->validate($request,[
                'user.phone' => 'required|regex:/^1[34578][0-9]{9}$/',
                'user.password' => 'required|min:4|max:20',
            ]);
            $SMScode = $request->input('SMScode');
            $phone = $request->input('user.phone');
            if($SMScode != Session::get('SMScode') || $phone != Session::get('captchaPhone'))
                return redirect()->back()->withInput($request->input())->with('msg','手机验证码错误');
            $user = Users::where('phone',$phone)->first();
            if(!$user)
                return redirect()->back()->withInput('user')->with('msg','用户不存在！');
            $user->password = $request->input('user.password');
            $user->save();
            Session::put('user',$user->id);
            $log2url = Session::get('log2url');
            return redirect($log2url ?: '/')->cookie('userPhone',$request->input('user.phone'),1440)->cookie('userPassword',$request->input('user.password'),1440)->with('msg','重置密码成功');
        }
    } //重置密码
    public function userInfo()
    {
        $user = Users::find(Session::get('user'));
        return view('user.userInfo',['user' => $user]);
    } //个人信息
    public function varyUser(Request $request)
    {
        if($request->isMethod('get'))
        {
            $user = Users::find(Session::get('user'));
            return view('user.varyUser',['user' => $user]);
        }
        else if($request->isMethod('post'))
        {
            $this->validate($request,[
                'user.name' => 'required|min:2|max:4',
                'user.gender' => 'required|integer',
                'user.birth' => 'required|date',
                'user.school' => 'required',
                'user.major' => 'required',
                'user.class' => 'required|integer',
            ]);
            Users::find(Session::get('user'))->update($request->input('user'));
            return redirect()->back()->with('msg','个人信息修改成功');
        }
    } //修改个人信息
    public function uploadPhoto(Request $request)
    {
        $image = $request->input('file');
        if(file_put_contents(storage_path('app/upload/user/photo/' . Session::get('user') . '.jpg'),base64_decode(substr($image,23))))
            echo json_encode(['status' => 1,'msg' => '头像上传成功']);
        else
            echo json_encode(['status' => 0,'msg' => '头像上传失败']);
    } //上传头像
    public function viewPhoto($id)
    {
        if(!Session::has('user') && !Session::has('manager'))
            return redirect()->back()->with('msg','没有权限');
        header("Content-Type:image/jpg");
        if(Storage::disk('upload')->exists('user/photo/' . $id))
            echo Storage::disk('upload')->get('user/photo/' . $id);
        else
            echo Storage::disk('public')->get('user/defaultPhoto.jpg');
    } //查看头像
    public function captcha4SendSMS(Request $request)
    {
        $rules = ['captcha' => 'required|captcha','phone' => 'required|regex:/^1[34578][0-9]{9}$/'];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails())
            echo json_encode(['status' => 4,'msg' => '验证码错误'],JSON_UNESCAPED_UNICODE);
        else
        {
            Session::put('captchaPhone',$request->input('phone'));
            echo json_encode($this->sendSMScode($request->input('phone')),JSON_UNESCAPED_UNICODE);
        }
    } //校验图形验证码并发送手机验证码
    public function apply(Request $request,$id)
    {
        $department = Department::find($id);
        $association = $department->association;
        if(Application::where(['aid' => $association->id,'uid' => Session::get('user')])->first())
            return redirect(route('association',[$association->id]))->with('msg','你已报名该社团，不可重复报名');
        else if($department->round != 0)
            return redirect(route('association',[$association->id]))->with('msg','该部门已开始面试，无法报名');
        if($request->isMethod('get'))
        {
            $user = Users::find(Session::get('user'));
            return view('user.application',['user' => $user,'did' => $id,'require_info' => json_decode($association->require_info),'applicationName' => $association->name . $department->name,'act' => 'apply']);
        }
        else if($request->isMethod('post'))
        {
            $require_info = [];
            foreach($request->input('require_info') as $data)
                array_push($require_info,$data);
            $require_info = json_encode($require_info,JSON_UNESCAPED_UNICODE);
            Application::create
            ([
                'uid' => Session::get('user'),
                'aid' => $association->id,
                'did' => $department->id,
                'require_info' => $require_info,
                'round' => 0,
            ]);
            return redirect('userApplication')->with('msg','报名成功！');
        }
    } //报名社团某个部门
    public function myApplication($id = null)
    {
        if($id)
        {
            $user = Users::find(Session::get('user'));
            $application = Application::find($id);
            $association = $application->association;
            $department = $application->department;
            return view('user.application',['user' => $user,'application' => $application,'applicationName' => $association->name . $department->name,'act' => 'myApplicationDetail','require_info' => json_decode($association->require_info,true)]);
        }
        else
        {
            $application = Application::where(['uid' => Session::get('user')])->get();
            return view('user.myApplication',['applications' => $application]);
        }

    } //所有报过名的社团以及某张报名表的详细信息
    private function sendSMScode($phone)
    {
        if(Session::has('lastSMStime') && (time() - Session::get('lastSMStime') < 300))
            return ['status' => 3,'msg' => '已发送短信，请勿反复调用'];
        $url = 'https://api.miaodiyun.com/20150822/industrySMS/sendSMS';
        $accountSid = '40d2b129465c4648a01ca48517fd1f01';
        $timestamp = date('YmdHis');
        $sig = md5('40d2b129465c4648a01ca48517fd1f01e36726660a6a4707ba57b86cc4f86cc7' . $timestamp);
        $SMScode = mt_rand(1000,9999);
        $content = '【线上报名】你的验证码是'.$SMScode.'，该验证码用于GDPU团委的线上招新系统，若非本人操作请忽略。';
        $data =
        [
            'accountSid' => $accountSid,
            'smsContent' => $content,
            'to' => $phone,
            'timestamp' => $timestamp,
            'sig' => $sig,
        ];
        $curlRs = $this->curl($url,'post','json',$data);
        if($curlRs['respCode'] == 00000)
        {
            Session::put('SMScode',$SMScode);
            Session::put('captchaPhone',$phone);
            Session::put('lastSMStime',time());
            $this->operated(null,1,$phone);
            return ['status' => 1,'msg' => '短信发送成功'];
        }
        else
            return ['status' => 0,'msg' => '短信发送失败'];
    } //发送短信
    private function curl($url,$type=null,$res=null,$data=null,$header=null,$useCookie=null,$cookie=null)
    {
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
        if($type == 'post')
        {
            curl_setopt($curl,CURLOPT_POST,1);
            curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($data));
        }
        if($header)
        {
            $headerData = [];
            foreach($header as $key => $value)
                array_push($headerData,$key.': ' . $value);
            curl_setopt($curl,CURLOPT_HTTPHEADER,$headerData);
        }
        if($useCookie == 'get')
        {
            curl_setopt($curl,CURLOPT_HEADER,1);
            $content = curl_exec($curl);
            curl_close($curl);
            preg_match('/Set-Cookie:(.*);/iU',$content,$str);
            $cookie = $str[1];
            $content = explode("\r\n", $content);
            $body = $content[count($content)-1];
            return $res == 'json' ? [$cookie,json_encode($body)] : [$cookie,$body];
        }
        else if($useCookie == 'with')
            curl_setopt($curl,CURLOPT_COOKIE,$cookie);
        $response = curl_exec($curl);
        curl_close($curl);
        return  $res == 'json' ? json_decode($response,true) : $response;
    }
    private function operated($form_id,$type,$detail)
    {
        Operation::create(['id' => null,'from_id' => $form_id,'type' => $type,'detail' => $detail]);
    } //记录敏感操作
}