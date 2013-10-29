function generate_tasks(infilename,measurefunction,wind,methodid,tbeg,tend,tstep);
% Функция для генерации набора заданий для заданного временного ряда
% Входные параметры:
% infilename - имя входного файла, который будет анализироваться
% wind - окно рассчета
% nfragments - кол-во фрагментов, на которые нужно разбить задание (по
%              умолчанию - 20
% methodid - ид метода оконног рассчета (c реализацией функции рассчета конкретной меры)
% tbeg,tend - начало и конец интервала последней точки окна, диапазон
%             которых нужно проанализировать. По умолчанию - wind+1 и n
% measurefunction - текст матлаб-команды для вызова функции рассчета меры
%                   используйте переменную y_fragment для переменной окна и
%                   конкретные константы для задания параметров рассчета.
if (nargin<5)
    tbeg=wind+1;
end
y=dlmread(infilename);
n=length(y);
if (nargin<6)
    tend=n;
end
if (nargin<7)
    tstep=1;
end


userid=1;
descr='CalcInWindow';
resid=1;% id результирующего ряда!!! Нужно наверное создавать его при загрузке задания
server='http://127.0.0.1:80/';
%server='http://prognoz.ck.ua:80/';

% для добавления нового ряда:
%/TS/resultseries.php?action=new
url=strcat(server,['TS/resultseries.php?action=new']);
% параметры lastdate, lasttime, name, type
params = {'lastdate','0000-00-00', 'lasttime','00:00:00','name','newseries',...
    'type',num2str(1),'doitdas4dsa454a6s54da65s4a6s5d4a6s5','1'}; 
data=urlread(url,'POST',params);
[resid,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
data=data(NEXTINDEX:length(data));

dataid=upload_data(infilename,'File_ForCalculation.rar',userid,descr,server);
fp=fopen('commands.m','w');
for i=tbeg:tstep:tend
    fprintf(fp,'calculate_task(%c%s%c,%c%s%c,%d,%d,%d)\n',39,measurefunction,39,39,infilename,39,wind,i,resid);
end
fclose(fp);

predictminutes=60;
taskgroupdata=0;
[taskid,taskgroupid]=add_tasks_file('commands.m',methodid,dataid,userid,predictminutes,taskgroupdata,server);
taskgroupdata2=taskgroupid;
%add_task(command_i,methodid,dataid,userid,predictminutes,taskgroupdata2,server);
