<?php

namespace App\Http\Controllers;

use App\Application;
use App\Association;
use App\Department;
use App\Manager;
use App\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ManagerController extends Controller
{
    public function login(Request $request)
    {
        if($request->isMethod('get'))
            return view('manager.log');
        else if($request->isMethod('post'))
        {
            $manager = Manager::where($request->input('manager'))->first();
            if($manager)
            {
                Session::put('manager',$manager->id);
                Session::put('managerType',$manager->type);
                if($manager->type == 2)
                    return redirect('teacher');
                else
                    return redirect('admin');
            }
            else
            {
                Session::forget('manager');
                Session::forget('managerType');
                return redirect()->back()->withInput($request->input('manager'))->with('msg','用户密码错误');
            }
        }
    } //登录
    public function logout()
    {
        Session::forget('manager');
        Session::forget('managerType');
        return redirect('adminLog');
    }
    public function index()
    {
        $manager = Manager::find(Session::get('manager'));
        if($manager->type == 2)
            return redirect('teacher');
        if(!in_array($manager->type,[1,2]))
        {
            $association = $manager->aid == 0 ? null : $manager->association;
            $department = $manager->did == 0 ? null : $manager->department;
            if(Session::get('managerType') == 3)
            {
                $applicationNum = $manager->aid == 0 ? '请先创建社团' : Application::where('aid',$association->id)->count();
                $departmentRound = 0;
            }
            else
            {
                $applicationNum = Application::where('did',$department->id)->count();
                $departmentRound = $manager->did == 0 ? '未开始' : $department->getRound();
            }
        }
        else
        {
            $association = null;
            $department = null;
            $applicationNum = Application::count();
            $departmentRound = null;
        }
        $departmentRound = $departmentRound == '零' ? '未开始' : $departmentRound;
        $departmentRound = $departmentRound == 100 ? '已结束' : $departmentRound;
        return view('manager.index',['manager' => $manager,'association' => $association,'department' => $department,'applicationNum' => $applicationNum,'type' => Session::get('managerType'),'departmentRound' => $departmentRound]);
    } //管理员首页
    public function teacher()
    {
//        if(!in_array(Session::get('managerType'),[1,2]))
//            return redirect()->back()->with('msg','没有权限！');
        $total = DB::select('select count(distinct(application.uid)) as total from application join department on application.did = department.id ')[0]->total;
        $associationNum = DB::select('select association.name as name,count(distinct(application.uid)) as count from application join association on application.aid = association.id join department on application.did = department.id  group by association.name');
        $schoolNum = DB::select('select user.school as name,count(distinct(application.uid)) as count from application join department on application.did = department.id join user on application.uid = user.id  group by user.school');
        $majorNum = DB::select('select user.major as name,count(distinct(application.uid)) as count from application join department on application.did = department.id join user on application.uid = user.id  group by user.major');
        $managers = DB::select('select manager.name as name,manager.phone as phone,association.name as association from manager join association on manager.aid = association.id where manager.type = 3 order by manager.aid');
//        dd($total);
        return view('manager.teacher',['total' => $total,'associationNum' => $associationNum,'schoolNum' => $schoolNum,'majorNum' => $majorNum,'managers' => $managers]);
    } //老师首页
    public function resetPassword(Request $request)
    {
        if($request->isMethod('get'))
            return view('manager.resetPassword');
        else if($request->isMethod('post')) {
            $this->validate($request, [
                'manager.phone' => 'required|regex:/^1[34578][0-9]{9}$/',
                'manager.password' => 'required|min:4|max:20',
            ]);
            $SMScode = $request->input('SMScode');
            $phone = $request->input('manager.phone');
            if ($SMScode != Session::get('SMScode') || $phone != Session::get('captchaPhone'))
                return redirect()->back()->withInput($request->input())->with('msg', '手机验证码错误');
            $manager = Manager::where('phone', $phone)->first();
            if (!$manager)
                return redirect()->back()->withInput('manager')->with('msg', '用户不存在！');
            $manager->password = $request->input('manager.password');
            $manager->save();
            Session::put('manager', $manager->id);
            $log2url = Session::get('log2url');
            return redirect('admin')->with('msg','重置密码成功！');
        }
    }
    public function association($id = null)
    {
        if($id)
        {
            $association = Association::find($id);
            $departments = Department::where('aid',$id)->get();
            $apply = false;
            if(Session::has('user'))
            {
                $apply = Application::where([['aid','=',$association->id],['uid','=',Session::get('user')]])->first();
                if($apply)
                    $apply = $apply->did;
            }
            return view('user.association',['association' => $association,'departments' => $departments,'apply' => $apply]);
        }
    } //社团首页
    public function department($id = null)
    {
        if($id)
        {
            $sign = false;
            $department = Department::find($id);
            if(Session::has('user'))
            {
                $aid = $department->association->id;
                $sign = Application::where([['aid','=',$aid],['uid','=',Session::get('user')]])->exists();
            }
            return view('user.department',['department' => $department,'sign' => $sign]);
        }
    } //部门首页
    public function createAssociation(Request $request)
    {
        $manager = Manager::find(Session::get('manager'));
        if(!in_array($manager->type,[3]))
            return redirect()->back()->with('msg','没有权限！');
        else if($manager->aid != 0)
            return redirect()->back()->with('msg','已创建社团');
        if($request->isMethod('get'))
            return view('manager.varyAssociation',['act' => 'create']);
        else if($request->isMethod('post'))
        {
            $this->validate($request,[
                'association.name' => 'required|unique:association',
                'association.short' => 'required',
                'association.introduce' => 'required',
                'association.require_info' => 'required',
            ]);
            $data = $request->input('association');
            $data['require_info'] = json_encode(explode(' ',$data['require_info']),JSON_UNESCAPED_UNICODE);
            $association = Association::create($data);
            $manager->update(['aid' => $association->id]);
            return redirect('admin')->with('msg','创建成功！');
        }
    } //社团管理员创建社团
    public function createDepartment(Request $request)
    {
        $manager = Manager::find(Session::get('manager'));
        if(!in_array($manager->type,[3]))
            return redirect()->back()->with('msg','没有权限！');
//        else if($manager->did != 0)
//            return redirect()->back()->with('msg','已创建部门');
        if($request->isMethod('get'))
            return view('manager.varyDepartment',['act' =>' create']);
        else if($request->isMethod('post'))
        {
            $this->validate($request,[
                'department.name' => 'required',
//                'department.short' => 'required',
                'department.introduce' => 'required',
            ]);
            $aid = Manager::find(Session::get('manager'))->aid;
            $info = $request->input('department');
            $info['aid'] = $aid;
            $info['round'] = 0;
            $department = Department::create($info);
//            $manager->update(['did' => $department->id]);
            return redirect('admin')->with('msg','创建成功！');
        }
    } //社团创建部门
    public function varyAssociation(Request $request)
    {
        if(!in_array(Session::get('managerType'),[3]))
            return redirect()->back()->with('msg','没有权限！');
        $association = Association::find(Manager::find(Session::get('manager'))->aid);
        if($request->isMethod('get'))
        {
            $data = $association->require_info;
            $data = json_decode($data);
            $require_info = '';
            for($i = 0;$i < count($data);$i++)
            {
                if($i == count($data) - 1)
                    $require_info .= $data[$i];
                else
                    $require_info .= $data[$i] . ' ';
            }
            $association->require_info = $require_info;
            return view('manager.varyAssociation',['act' => 'vary','association' => $association]);
        }
        else if($request->isMethod('post'))
        {
            $this->validate($request,[
                'association.name' => 'required|unique:association,name,' . $association->id,
                'association.short' => 'required',
                'association.introduce' => 'required',
                'association.require_info' => 'required',
            ]);
            $data = $request->input('association');
            $data['require_info'] = json_encode(explode(' ',$data['require_info']),JSON_UNESCAPED_UNICODE);
            $association->update($data);
            return redirect()->back()->with('msg','修改成功！');
        }
    } //修改社团信息
    public function varyDepartment(Request $request)
    {
        if(!in_array(Session::get('managerType'),[3,4]))
            return redirect()->back()->with('msg','没有权限！');
        $department = Department::find(Manager::find(Session::get('manager'))->did);
        if($request->isMethod('get'))
            return view('manager.varyDepartment',['act' => 'vary','department' => $department]);
        else if($request->isMethod('post'))
        {
            $this->validate($request,[
                'department.name' => 'required',
//                'department.short' => 'required',
                'department.introduce' => 'required',
            ]);
            $department->update($request->input('department'));
            return redirect()->back()->with('msg','修改成功！');
        }
    } //修改部门信息
    public function varyManager(Request $request)
    {
        if($request->isMethod('get'))
        {
            $manager = Manager::find(Session::get('manager'));
            return view('manager.varyManager')->with(['act' => 'vary','manager' => $manager]);
        }
        else if($request->isMethod('post'))
        {
            $manager = Manager::find(Session::get('manager'));
            $this->validate($request,[
                'manager.phone' => 'required|integer|unique:manager,phone,' . $manager->id,
                'manager.name' => 'required|unique:manager,name,' . $manager->id,
                'manager.password' => 'min:4|max:20',
            ]);
            $manager->update($request->input('manager'));
            return redirect('manager')->with('msg','修改成功');
        }
    } //修改管理员信息
    public function createManager(Request $request)
    {
        if(!in_array(Session::get('managerType'),[1]))
            return redirect()->back()->with('msg','没有权限');
        if($request->isMethod('get'))
            return view('manager.createManager');
        else if($request->isMethod('post'))
        {
            $this->validate($request,[
                'manager.phone' => 'required|unique:manager|regex:/^1[34578][0-9]{9}$/',
                'manager.name' => 'required|unique:manager',
                'manager.password' => 'required',
            ]);
            $info = $request->input('manager');
            $info['aid'] = 0;
            $info['did'] = 0;
            $info['type'] = 3;
            Manager::create($info);
            return redirect('myManager')->with('msg' ,'新增成功');
        }
    } //超级管理员创建社团管理员
    public function addManager(Request $request)
    {
        if(!in_array(Session::get('managerType'),[3]))
            return redirect()->back()->with('msg','没有权限');
        if($request->isMethod('get'))
            return view('manager.varyManager')->with(['act' => 'add','departments' => Department::where('aid',Manager::find(Session::get('manager'))->aid)->get()]);
        else if($request->isMethod('post'))
        {
            $info = $request->input('manager');
            $manager = Manager::find(Session::get('manager'));
            $this->validate($request,[
                'manager.phone' => 'required|unique:manager|regex:/^1[34578][0-9]{9}$/',
                'manager.name' => 'required|unique:manager',
                'manager.password' => 'required',
                'manager.type' => 'required|integer',

            ]);
            $info['aid'] = $manager->aid;
            if($info['type'] == 4)
            {
                if(! Department::where([['aid','=',$manager->aid],['id','=',$info['did']]])->first())
                    return redirect()->back()->withInput($request->input())->with('msg','没有权限！');
            }
            else
                $info['did'] = 0;
            if($info['type'] < Session::get('managerType'))
                return redirect()->back()->withInput($request->input())->with('msg','没有权限！');
            Manager::create($info);
            return redirect('myManager')->with('msg','创建成功');
        }
    } //社团新增管理员
    public function chooseDepartment(Request $request)
    {
        if($request->isMethod('get'))
        {
            $manager = Manager::find(Session::get('manager'));
            $departments = Department::where('aid',$manager->aid)->get();
            return view('manager.chooseDepartment',['departments' => $departments]);
        }
        else if($request->isMethod('post'))
        {
            if(!in_array(Session::get('managerType'),[3]))
                return redirect()->back()->with('msg','没有权限！');
            $manager = Manager::find(Session::get('manager'));
            if($manager->did != 0)
                return redirect()->back()->with('msg','非法操作！');
            $this->validate($request,[
                'manager.did' => 'required|integer',
            ]);
            $did = $request->input('manager.did');
            $department = Department::find($did);
            if($department && $department->aid != $manager->aid)
                return redirect()->back()->with('msg','没有权限！');
            $manager->did = $did;
            $manager->save();
            return redirect('admin')->with('msg','选择部门成功');
        }
    } //管理员选择部门
    public function upgradeManager(Request $request)
    {
        $manager = Manager::find(Session::get('manager'));
        if(!in_array($manager->type,[3]))
            return redirect()->back()->with('msg','没有权限！');
        $this->validate($request,[
            'manager.id' => 'required|integer',
        ]);
        $upgrader = Manager::find($request->input('manager')['id']);
        if($upgrader->did != $manager->did)
            return redirect()->back()->with('msg','没有权限！');
        $upgrader->update(['type' => 3]);
        return redirect()->back()->with('msg','提升成功！');
    } //管理员升职手下
    public function manager($id = null)
    {
        if($id)
            $manager = Manager::find($id);
        else
            $manager = Manager::find(Session::get('manager'));
    } //管理员首页
    public function departmentApplication($id = null)
    {
        if($id)
        {
            $application = Application::find($id);
            return view('manager.application',['application' => $application,'act' => 'view&pass','user' => $application->user,'departmentRound' => $application->department->round,'require_info' => json_decode($application->require_info),'association' => $application->association]);
        }
        else
        {
            $manager = Manager::find(Session::get('manager'));
            $users = DB::table('user')->join('application','user.id','=','application.uid')->where('application.did',$manager->did)->select('user.id as id','application.id as aid','name','gender','phone','major','school','class','round')->get();
            return view('manager.departmentApplication',['users' => $users,'departmentRound' => $manager->department->round]);
        }
    } //查看部门的所有报名表或者一张详情
    public function associationApplication()
    {
            $manager = Manager::find(Session::get('manager'));
            $users = DB::table('user')->join('application','user.id','=','application.uid')->join('department','application.did','=','department.id')->where('application.aid',$manager->aid)->select('user.id as id','application.id as aid','department.name as departmentName','user.name as name','gender','phone','major','school','class')->orderBy('departmentName')->orderBy('major')->get();
            $departments = Department::where('aid',$manager->association->id)->get();
            $departmentNum = [];
            for($i = 0;$i < count($departments);$i++)
                $departmentNum[$i] = [$departments[$i]->name,Application::where('did',$departments[$i]->id)->count()];
            return view('manager.associationApplication',['users' => $users,'departmentNum' => $departmentNum]);
    } //查看社团的所有报名表或者一张详情
    public function myDepartment()
    {
        if(!in_array(Session::get('managerType'),[3,4]))
            return redirect()->back()->with('msg','没有权限！');
        $aid = Manager::find(Session::get('manager'))->aid;
        $department = Department::where(['aid' => $aid])->get();
        $managerNum = [];
        for($i = 0;$i < count($department);$i++)
            $managerNum[$i] = Manager::where(['did' => $department[$i]->id])->count();
        return view('manager.myDepartment',['department' => $department,'managerNum' => $managerNum]);
    } //社团所有部门
    public function myManager()
    {
        $manager = Manager::find(Session::get('manager'));
        if(!in_array($manager->type,[1,3,4]))
            return redirect()->back()->with('msg','没有权限！');
        if($manager->type == 3 || $manager->type == 4)
            $managers = Manager::where('aid',$manager->aid)->orderBy('type')->orderBy('aid')->orderBy('did')->get();
        else if($manager->type == 1)
            $managers = Manager::where('id','!=',$manager->id)->orderBy('type')->orderBy('aid')->orderBy('did')->get();
        return view('manager.myManager',['managers' => $managers]);
    } //所有管理员
    public function nextRound()
    {
        $department = Manager::find(Session::get('manager'))->department;
        if($department->round == 100)
            echo json_encode(['status' => '0','msg' => '部门招新已经结束'],JSON_UNESCAPED_UNICODE);
        else
        {
            $department->increment('round');
            echo json_encode(['status' => 1,'msg' => '进入第' . $department->getRound() . '轮'],JSON_UNESCAPED_UNICODE);
        }
    } //部门进入下一轮
    public function note(Request $request)
    {
        $id = $request->input('id');
        $note = $request->input('note');
        $manager = Manager::find(Session::get('manager'));
        $application  = Application::find($id);
        if($application->did != $manager->did)
            echo json_encode(['status' => 0,'msg' => '没有权限']);
        else
        {
            $application->note = $note;
            $application->save();
            echo json_encode(['status' => 1,'msg' => '备注成功']);
        }
    } //增加备注
    public function finish()
    {
        $manager = Manager::find(Session::get('manager'));
        $department = $manager->department;
        $applications = Application::where([['did','=',$department->id],['round','=',$department->round]])->get();
        if($department->round != 100 && $department->round != 0)
        {
            $department->update(['round' => 100]);
            foreach ($applications as $a)
                $a->update(['round' => 100]);
            echo json_encode(['status' => 1,'msg' => '部门招新结束，感谢使用！']);
        }
        else if($department->round == 100)
            echo json_encode(['status' => 0,'msg' => '部门招新已经结束！']);
        else if($department->round == 0)
            echo json_encode(['status' => 0,'msg' => '部门招新尚未开始！']);
    } //部门完成面试，招新结束
    public function pass(Request $request)
    {
        $manager = Manager::find(Session::get('manager'));
        $id = $request->input('application.id');
        $application = Application::find($id);
        if($application->did != $manager->did)
            return redirect()->back()->with('msg','没有权限');
        $round = $manager->department->round;
        if($round == 100)
            return redirect()->back()->with('msg','部门招新已经结束');
        if($application->round >= $round)
            return redirect()->back()->with('msg','报名状态不正确，操作失败');
        else
        {
            $application->update(['round' => $round]);
            return redirect('departmentApplication')->with('msg','通过操作成功');
        }
    } //部门管理员通过报名者
    public function getAssociationDataExcel()
    {
        $manager = Manager::find(Session::get('manager'));
        if($manager->type >= 4)
            return redirect()->back()->with('msg','没有权限');
        $association = $manager->association;
        $application = Application::where(['aid' => $association->id])->get();
        $fileName =  $association->name . '-' . '招新资料表';
        $data[0] = ['编号','姓名','电话','性别','生日','学院','专业','班级','部门'];
        for($i = 0;$i < count($application);$i++)
        {
            $user = $application[$i]->user;
            $data[$i + 1] =  [$user->id,$user->name,$user->phone,$user->getGender(),$user->birth,$user->school,$user->major,$user->class,$application[$i]->department->name];
        }
        Excel::create($fileName,function($excel) use($fileName,$data)
        {
            $excel->setTitle($fileName);
            array_unshift($data,[$fileName]);
            $row = count($data);
            $col = count($data[1]);
            $excel->sheet('sheet1',function($sheet) use($data,$row,$col)
            {
                $e = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $sheet->rows($data);
                $sheet->setAllBorders('thin');
                $sheet->setWidth('A',5);
                $sheet->setWidth('B',10);
                $sheet->setWidth('C',15);
                $sheet->setWidth('D',5);
                $sheet->setWidth('E',10);
                $sheet->setWidth('F',10);
                $sheet->setWidth('G',10);
                $sheet->setWidth('H',5);
                $sheet->setWidth('I',10);
                $sheet->mergeCells('A1:' . $e[$col - 1] . '1');
                $sheet->setHeight(1, 50);
                $sheet->cell('A1', function($cell)
                {
                    $cell->setValignment('center');
                    $cell->setFontSize(16);
                });
                $sheet->cells('A2:' . $e[$col - 1] . '2',function($cells)
                {
                    $cells->setFontSize(11);
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A1:' . $e[$col - 1] . $row,function($cells)
                {
                    $cells->setAlignment('center');
                });
            });
        })->download('xls');
    }// 获得社团报名资料表格
    public function getDataExcel()
    {
        $manager = Manager::find(Session::get('manager'));
        $association = $manager->association;
        $department = $manager->department;
        $application = Application::where(['did' => $department->id])->get();
        $fileName =  $association->name . '-' . $department->name . '-' . '招新资料表';
        $data[0] = ['编号','姓名','电话','性别','生日','学院','专业','班级','备注'];
        for($i = 0;$i < count($application);$i++)
        {
            $user = $application[$i]->user;
            $data[$i + 1] =  [$user->id,$user->name,$user->phone,$user->getGender(),$user->birth,$user->school,$user->major,$user->class,$application[$i]->note];
        }
        Excel::create($fileName,function($excel) use($fileName,$data)
        {
            $excel->setTitle($fileName);
            array_unshift($data,[$fileName]);
            $row = count($data);
            $col = count($data[1]);
            $excel->sheet('sheet1',function($sheet) use($data,$row,$col)
            {
                $e = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $sheet->rows($data);
                $sheet->setAllBorders('thin');
                $sheet->setWidth('A',5);
                $sheet->setWidth('B',10);
                $sheet->setWidth('C',15);
                $sheet->setWidth('D',5);
                $sheet->setWidth('E',10);
                $sheet->setWidth('F',10);
                $sheet->setWidth('G',10);
                $sheet->setWidth('H',5);
                $sheet->setWidth('I',50);
                $sheet->mergeCells('A1:' . $e[$col - 1] . '1');
                $sheet->setHeight(1, 50);
                $sheet->cell('A1', function($cell)
                {
                    $cell->setValignment('center');
                    $cell->setFontSize(16);
                });
                $sheet->cells('A2:' . $e[$col - 1] . '2',function($cells)
                {
                    $cells->setFontSize(11);
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A1:' . $e[$col - 1] . $row,function($cells)
                {
                    $cells->setAlignment('center');
                });
            });
        })->download('xls');
    } //获得部门报名资料表格
    public function getSignExcel()
    {
        $manager = Manager::find(Session::get('manager'));
        $department = $manager->department;
        $application = Application::where([['did',$manager->did],['round',$department->round - 1]])->get();
        $fileName = $manager->association->name . '-' . $department->name . '-第' . $department->getRound() . '轮招新签到表';
        $data[0] = ['编号','姓名','性别','手机','签到'];
        for($i = 0;$i < count($application);$i++)
        {
            $user = $application[$i]->user;
            $data[$i + 1] = [$user->id,$user->name,$user->getGender(),$user->phone,null];
        }
        Excel::create($fileName,function($excel) use($fileName,$data)
        {
            $excel->setTitle($fileName);
            array_unshift($data,[$fileName]);
            $row = count($data);
            $col = count($data[1]);
            $excel->sheet('sheet1',function($sheet) use($data,$row,$col)
            {
                $e = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $sheet->rows($data);
                $sheet->setAllBorders('thin');
                $sheet->setWidth('A',5);
                $sheet->setWidth('B',10);
                $sheet->setWidth('C',5);
                $sheet->setWidth('D',15);
                $sheet->setWidth('E',15);
                $sheet->mergeCells('A1:' . $e[$col - 1] . '1');
                $sheet->setHeight(1, 50);
                $sheet->cell('A1', function($cell)
                {
                    $cell->setValignment('center');
                    $cell->setFontSize(16);
                });
                $sheet->cells('A2:' . $e[$col - 1] . '2',function($cells)
                {
                    $cells->setFontSize(11);
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A1:' . $e[$col - 1] . $row,function($cells)
                {
                    $cells->setAlignment('center');
                });
            });
        })->download('xls');
    } //获得部门面试签到表格
    public function getInterviewExcel()
    {
        $manager = Manager::find(Session::get('manager'));
        $association = $manager->association;
        $department = $manager->department;
        $application = Application::where('did',$department->id)->get();
        $extra = json_decode($association->require_info,true);
        $data[0] = array_merge(['编号','姓名','性别','学院','专业'],$extra,['备注']);
        for($i = 0;$i < count($application);$i++)
        {
            $user = $application[$i]->user;
            $require_info = json_decode($application[$i]->require_info);
            $data[$i + 1] = array_merge([$user->id,$user->name,$user->getGender(),$user->school,$user->major],$require_info,[$application[$i]->note]);
        }
        $fileName = $association->name . '-' . $department->name . '-面试记录表';
        Excel::create($fileName,function($excel) use($fileName,$data)
        {
            $excel->setTitle($fileName);
            array_unshift($data,[$fileName]);
            $row = count($data);
            $col = count($data[1]);
            $excel->sheet('sheet1',function($sheet) use($data,$row,$col)
            {
                $e = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $sheet->rows($data);
                $sheet->setAllBorders('thin');
                $sheet->setWidth('A',5);
                $sheet->setWidth('B',10);
                $sheet->setWidth('C',5);
                $sheet->setWidth('D',10);
                $sheet->setWidth('E',10);
                $sheet->mergeCells('A1:' . $e[$col - 1] . '1');
                $sheet->setHeight(1, 50);
                $sheet->cell('A1', function($cell)
                {
                    $cell->setValignment('center');
                    $cell->setFontSize(16);
                });
                for($i = 5;$i < $col;$i++)
                {
                    $sheet->setWidth($e[$i],30);
                    for($j = 3;$j <= $row;$j++)
                        $sheet->getStyle($e[$i] . $j)->getAlignment()->setWrapText(true);
                }
                for($i = 3;$i < $row;$i++)
                    $sheet->setHeight($i,60);
                $sheet->cells('A2:' . $e[$col - 1] . '2',function($cells)
                {
                    $cells->setFontSize(11);
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A1:' . $e[$col - 1] . $row,function($cells)
                {
                    $cells->setAlignment('center');
                    $cells->setValignment('top');
                });
            });
        })->export('xls');
    } //获得部门面试记录表格
    public function sendStartSMS(Request $request)
    {
        $time = $request->input('time');
        $place = $request->input('place');
        $manager = Manager::find(Session::get('manager'));
        $associationName = $manager->association->name;
        $departmentName = $manager->department->name;
        if($manager->department->round != 1)
            echo json_encode(['status' => 0,'msg' => '部门不是一轮面试时间'],JSON_UNESCAPED_UNICODE);
        else
        {
            $mobiles = DB::table('user')->join('application','user.id','=','application.uid')->pluck('phone')->toArray();
            $content = '【线上报名】同学，你好！你报名的'.$associationName.'-'.$departmentName.'的一轮面试即将开始，请于'.$time.'到'.$place.'参加面试。';
            $rs = $this->sendSMS($content,$mobiles);
            echo json_encode(['status' => $rs['status'],'msg' => $rs['msg']],JSON_UNESCAPED_UNICODE);
        }
    } //%s同学，你好！你报名的%s-%s的一轮面试即将开始，请于%s到%s参加面试。
    public function sendNextSMS(Request $request)
    {
        $time = $request->input('time');
        $place = $request->input('place');
        $manager = Manager::find(Session::get('manager'));
        $department = $manager->department;
        $associationName = $manager->association->name;
        $departmentName = $department->name;
        $content = '【线上报名】同学，你好，你通过了'.$associationName.'-'.$departmentName.'第'.$department->getRound().'轮面试，请于'.$time.'到'.$place.'参加下一轮面试。';
        $mobiles = DB::table('user')->join('application','user.id','=','application.uid')->where('application.round',$department->round - 1)->pluck('phone')->toArray();
        $rs = $this->sendSMS($content,$mobiles);
        echo json_encode(['status' => $rs['status'],'msg' => $rs['msg']],JSON_UNESCAPED_UNICODE);
    } //%s同学你好，你通过了%s-%s第%s面试，请于%s到%s参加下一轮面试。
    public function sendPassSMS(Request $request)
    {
        $time = $request->input('time');
        $place = $request->input('place');
        $manager = Manager::find(Session::get('manager'));
        $associationName = $manager->association->name;
        $departmentName = $manager->department->name;
        if($manager->department->round != 100)
            echo json_encode(['status' => 0,'msg' => '部门面试正在进行中'],JSON_UNESCAPED_UNICODE);
        else
        {
            $content = '【线上报名】同学，你好，在'.$associationName.'-'.$departmentName.'的面试中你表现优异，通过考核。部门见面会将于'.$time.'在'.$place.'举行，请按时参加。';
            $mobiles = DB::table('user')->join('application','user.id','=','application.uid')->where('application.round',100)->pluck('phone')->toArray();
            $rs = $this->sendSMS($content,$mobiles);
            echo json_encode(['status' => $rs['status'],'msg' => $rs['msg']],JSON_UNESCAPED_UNICODE);
        }
    } //%s同学你好，在%s-%s的面试中你表现优异，通过考核。部门见面会将于%s在%s举行，请按时参加。
    public function uploadAssociationBackground(Request $request)
    {
        if(!in_array(Session::get('managerType'),[3]))
            return redirect()->back()->with('msg','没有权限！');
        $file = $request->file('file');
        if(!$file)
            return redirect()->back()->with('msg',' 请选择图片');
        if(!in_array($file->getClientOriginalExtension(),['png']))
            return redirect()->back()->with('msg','请使用png格式的图片');
        $manager = Manager::find(Session::get('manager'));
        $file->storeAs('upload/manager/background/association',$manager->aid . '.png');
        return redirect()->back()->with('msg','上传成功');
    } //管理员上传社团介绍页面背景图
    public function uploadAssociationLogo(Request $request)
    {
        if(!in_array(Session::get('managerType'),[3]))
            return redirect()->back()->with('msg','没有权限！');
        $file = $request->file('file');
        if(!$file)
            return redirect()->back()->with('msg',' 请选择图片');
        if(!in_array($file->getClientOriginalExtension(),['png']))
            return redirect()->back()->with('msg','请使用png格式的图片');
        $manager = Manager::find(Session::get('manager'));
        $file->storeAs('upload/manager/logo/association',$manager->aid . '.png');
        return redirect()->back()->with('msg','上传成功');
    } //管理员上传社团logo
    public function uploadDepartmentBackground(Request $request)
    {
        $file = $request->file('file');
        if(!$file)
            return redirect()->back()->with('msg',' 请选择图片');
        if(!in_array($file->getClientOriginalExtension(),['png']))
            return redirect()->back()->with('msg','请使用png格式的图片');
        $manager = Manager::find(Session::get('manager'));
        $file->storeAs('upload/manager/background/department',$manager->did . '.png');
        return redirect()->back()->with('msg','上传成功');
    } //管理员上传部门介绍页面背景图
    public function viewAssociationBackground($id)
    {
        header("Content-Type:image/jpg");
        if(Storage::disk('upload')->exists('manager/background/association/' . $id))
            echo Storage::disk('upload')->get('manager/background/association/' . $id);
        else
            echo Storage::disk('public')->get('manager/background/defaultAssociationBackground.jpg');
    } //查看社团背景图
    public function viewDepartmentBackground($id)
    {
        header("Content-Type:image/jpg");
        if(Storage::disk('upload')->exists('manager/background/department/' . $id))
            echo Storage::disk('upload')->get('manager/background/department/' . $id);
        else
            echo Storage::disk('public')->get('manager/background/defaultDepartmentBackground.jpg');
    } //查看部门背景图
    public function viewAssociationLogo($id)
    {
        header("Content-Type:image/jpg");
        if(Storage::disk('upload')->exists('manager/logo/association/' . $id))
            echo Storage::disk('upload')->get('manager/logo/association/' . $id);
        else
            echo Storage::disk('public')->get('manager/logo/defaultAssociationLogo.jpg');
    } //查看社团logo
    private function sendSMS($content,$mobiles)
    {
        if(Session::has('lastSMStime') && (time() - Session::get('lastSMStime') < 300))
            return ['status' => 3,'msg' => '已发送短信，请勿反复调用'];
        $url = 'https://api.miaodiyun.com/20150822/industrySMS/sendSMS';
        $accountSid = '';
        $timestamp = date('YmdHis');
        $sig = md5('' . $timestamp);
        foreach($this->explode100($mobiles) as $mobile)
        {
            $data =
                [
                    'accountSid' => $accountSid,
                    'smsContent' => $content,
                    'to' => $mobile,
                    'timestamp' => $timestamp,
                    'sig' => $sig,
                ];
            $rs = $this->curl($url,'post','json',$data);
            if($rs['respCode'] != 00000)
                return ['status' => 2,'msg' => '短信发送失败（未知原因）：' . $rs['respCode']];
        }
        Session::put('lastSMStime',time());
        $this->operated(Session::get('manager'),2,count($mobiles));
        return ['status' => 1,'msg' => '短信发送成功'];
    } //发送短信
    private function explode100($data)
    {
        if($data == null || !is_array($data) || empty($data))
        {
            echo json_encode(['status' => 0,'msg' => '没有符合的发送对象'],JSON_UNESCAPED_UNICODE);
            exit();
        }
        if(count($data) > 100)
        {
            $index = 0;
            for($i = 0;$i < count($data);)
            {
                $bigData[$index] = '';
                for($j = 0;$j < 100 && $i < count($data);$i++,$j++)
                    $bigData[$index] .= $data[$i] . ',';
                $index++;
            }
            foreach($bigData as $bd)
                $bd = substr($bd,0,-1);
        }
        else
        {
            $bigData[0] = '';
            for($i = 0;$i < count($data);$i++)
                $bigData[0] .= $data[$i] . ',';
            $bigData[0] = substr($bigData[0],0,-1);
        }
        return $bigData;
    } //分割成存放多个最大容量为100的数组的大数组
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