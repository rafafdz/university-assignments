library IEEE;
use IEEE.STD_LOGIC_1164.ALL;

entity Basys3 is
    Port (
        sw          : in   std_logic_vector (15 downto 0);  -- SeÃ±ales de entrada de los interruptores -- Arriba   = '1'   -- Los 3 swiches de la derecha: 2, 1 y 0.
        btn         : in   std_logic_vector (4 downto 0);  -- SeÃ±ales de entrada de los botones       -- Apretado = '1'   -- 0 central, 1 arriba, 2 izquierda, 3 derecha y 4 abajo.
        led         : out  std_logic_vector (15 downto 0);  -- SeÃ±ales de salida  a  los leds          -- Prendido = '1'   -- Los 4 leds de la derecha: 3, 2, 1 y 0.
        clk         : in   std_logic;                      -- No Tocar - SeÃ±al de entrada del clock   -- Frecuencia = 100Mhz.
        seg         : out  std_logic_vector (7 downto 0);  -- No Tocar - Salida de las seÃ±ales de segmentos.
        an          : out  std_logic_vector (3 downto 0)   -- No Tocar - Salida del selector de diplay.
          );
end Basys3;

architecture Behavioral of Basys3 is

-- Inicio de la declaración de los componentes.

component Clock_Divider -- No Tocar
    Port (
        clk         : in    std_logic;
        speed       : in    std_logic_vector (1 downto 0);
        clock       : out   std_logic
          );
    end component;
    
component Display_Controller  -- No Tocar
    Port (  
        dis_a       : in    std_logic_vector (3 downto 0);
        dis_b       : in    std_logic_vector (3 downto 0);
        dis_c       : in    std_logic_vector (3 downto 0);
        dis_d       : in    std_logic_vector (3 downto 0);
        clk         : in    std_logic;
        seg         : out   std_logic_vector (7 downto 0);
        an          : out   std_logic_vector (3 downto 0)
          );
    end component;

component Debouncer  -- No Tocar
    Port (
        clk         : in    std_logic;
        datain      : in    std_logic_vector (4 downto 0);
        dataout     : out   std_logic_vector (4 downto 0)
        );
    end component;

component Reg
    Port (
        clock       : in    std_logic;
        load        : in    std_logic;
        up          : in    std_logic;
        down        : in    std_logic;
        datain      : in    std_logic_vector (11 downto 0);
        dataout     : out   std_logic_vector (11 downto 0)
          );
    end component;

component PC 
    Port (  
        clock       : in    std_logic;
        load        : in    std_logic;
        stalling    : in    std_logic;
        datain      : in    std_logic_vector (11 downto 0);
        dataout     : out   std_logic_vector (11 downto 0)
          );
    end component;
 
 component Status
        Port (  
            clock       : in    std_logic;
            c           : in    std_logic;
            z           : in    std_logic;
            n           : in    std_logic;
            o           : in    std_logic;
            dataout     : out   std_logic_vector (3 downto 0)
              );
        end component;
 
 component SP 
       Port ( 
            clock    : in  std_logic;
            load     : in  std_logic;                       
            up       : in  std_logic;
            down     : in  std_logic;
            datain   : in  std_logic_vector (11 downto 0);
            dataout  : out std_logic_vector (11 downto 0)
              );
      end component;

 component BP 
       Port ( 
            clock    : in  std_logic;
            load       : in  std_logic;
            datain   : in  std_logic_vector (11 downto 0);  
            dataout  : out std_logic_vector (11 downto 0)
              );
      end component;

 component RegisterCU 
       Port ( 
            clock    : in  std_logic;                        -- Se�al del clock (reducido).
            datain   : in  std_logic_vector (11 downto 0);   -- Se�ales de entrada de datos.
            dataout  : out std_logic_vector (11 downto 0);
            lit      : out std_logic_vector (11 downto 0);
            selAddSec : out std_logic;
            stalling   : out std_logic
              );
      end component;
      
 
 component MainMemory
    Port (
        clock       : in   std_logic;
        write       : in   std_logic;
        address     : in   std_logic_vector (11 downto 0);
        datain      : in   std_logic_vector (11 downto 0);
        dataout     : out  std_logic_vector (11 downto 0)
          );
    end component;
    
    
  -- DECLARACIONES HECHAS POR RAFA --
    
 component ALU
    Port ( a        : in  std_logic_vector (11 downto 0);
           b        : in  std_logic_vector (11 downto 0);
           sop      : in  std_logic_vector (2 downto 0);
           c        : out std_logic;
           z        : out std_logic;                 
           n        : out std_logic;
           o        : out std_logic;
           result   : out std_logic_vector (11 downto 0));
    end component;
    

  component AdderSubstractor
    Port ( a : in std_logic_vector (11 downto 0);
           b : in std_logic_vector (11 downto 0);
           e : in STD_LOGIC; -- Selector entre suma / resta
           c : out STD_LOGIC; -- Carry out. Solo sera usado en la ALU
           s : out std_logic_vector (11 downto 0));
    end component;
    
 component MuxAddress
    Port ( in_sp : in std_logic_vector (11 downto 0);
           in_add_sub : in std_logic_vector (11 downto 0);
           in_pc : in std_logic_vector (11 downto 0);
           in_reg : in std_logic_vector (11 downto 0);
           in_lit: in std_logic_vector (11 downto 0); 
           sel: in std_logic_vector (3 downto 0);
           out_mem : out std_logic_vector (11 downto 0));
   end component;
   
 component MuxDouble
    Port ( in0  : in std_logic_vector (11 downto 0);
           in1  : in std_logic_vector (11 downto 0);
           in2  : in std_logic_vector (11 downto 0);
           in3  : in std_logic_vector (11 downto 0);
           sel  : in std_logic_vector (1 downto 0);
           out_mux  : out std_logic_vector (11 downto 0));
     end component;
     
     
  component MuxSingle
    Port ( in0  : in std_logic_vector (11 downto 0);
           in1  : in std_logic_vector (11 downto 0);
           sel  : in STD_LOGIC;
           out_mux  : out std_logic_vector (11 downto 0));
    end component;
    
  component ControlUnit is
    Port (
        in_op      : in std_logic_vector (11 downto 0);
        in_status  : in std_logic_vector (3 downto 0);
        enableA    : out std_logic;
        enableB    : out std_logic;
        selA       : out std_logic_vector (1 downto 0);
        selB       : out std_logic_vector (1 downto 0);
        loadPC     : out std_logic;
        selALU     : out std_logic_vector (2 downto 0);
        W          : out std_logic;
        incSP      : out std_logic;
        decSP      : out std_logic;
        LSP        : out std_logic;
        sub        : out std_logic;
        selDin     : out  std_logic_vector (1 downto 0);
        selAdd     : out  std_logic_vector (3 downto 0);
        loadBP     : out std_logic;
        selBP      : out std_logic;
        selPC      : out std_logic
        );
   end component;
   
-- Fin de la declaraciÃ³n de los componentes.

-- Inicio de la declaraciÃ³n de seÃ±ales.

-- AGREGADOS POR RAFA PARA TESTEO!
signal clk_switch : std_logic := '0';
signal clock_selector : std_logic_vector(1 downto 0) := "10";


signal clock            : std_logic := '0';                     -- SeÃ±al del clock reducido.                 
            
signal dis_a            : std_logic_vector(3 downto 0) := (others => '0');  -- SeÃ±ales de salida al display A.    
signal dis_b            : std_logic_vector(3 downto 0) := (others => '0');  -- SeÃ±ales de salida al display B.     
signal dis_c            : std_logic_vector(3 downto 0) := (others => '0');  -- SeÃ±ales de salida al display C.    
signal dis_d            : std_logic_vector(3 downto 0) := (others => '0');  -- SeÃ±ales de salida al display D.  
signal r                : std_logic_vector(11 downto 0) := (others => '0');  -- SeÃ±ales de salida al display D.  

signal d_btn            : std_logic_vector(4 downto 0) := (others => '0');  -- SeÃ±ales de botones con antirrebote.


-- señales definidas por Rafa

signal pc_out        : std_logic_vector(11 downto 0) := (others => '0');
signal adder_sub_out1: std_logic_vector(11 downto 0) := (others => '0');
signal adder_sub_out2: std_logic_vector(11 downto 0) := (others => '0');
signal memory_out    : std_logic_vector(11 downto 0) := (others => '0');
signal mux_add_out   : std_logic_vector(11 downto 0) := (others => '0');
signal sp_out        : std_logic_vector(11 downto 0) := (others => '0');
signal mux_bp_out    : std_logic_vector(11 downto 0) := (others => '0');
signal bp_out        : std_logic_vector(11 downto 0) := (others => '0');
signal alu_out       : std_logic_vector(11 downto 0) := (others => '0');
signal reg_a_out     : std_logic_vector(11 downto 0) := (others => '0');
signal reg_b_out     : std_logic_vector(11 downto 0) := (others => '0');
signal status_out    : std_logic_vector(3 downto 0) := (others => '0');
signal cu_opcode_out : std_logic_vector(11 downto 0);
signal cu_lit_out    : std_logic_vector(11 downto 0);
signal mux_data_out  : std_logic_vector(11 downto 0) := (others => '0');
signal mux_a_out     : std_logic_vector(11 downto 0) := (others => '0');
signal mux_b_out     : std_logic_vector(11 downto 0) := (others => '0');
signal mux_pc_out    : std_logic_vector(11 downto 0) := (others => '0');
signal alu_c_out     : std_logic;
signal alu_z_out     : std_logic;
signal alu_n_out     : std_logic;
signal alu_o_out     : std_logic;

-- señales CU

signal sig_enableA    : std_logic;
signal sig_enableB    : std_logic;
signal sig_selA       : std_logic_vector (1 downto 0);
signal sig_selB       : std_logic_vector (1 downto 0);
signal sig_loadPC     : std_logic;
signal sig_selALU     : std_logic_vector (2 downto 0);
signal sig_W          : std_logic;
signal sig_incSP      : std_logic;
signal sig_decSP      : std_logic;
signal sig_LSP        : std_logic;
signal sig_sub        : std_logic;
signal sig_selDin     : std_logic_vector (1 downto 0);
signal sig_selAdd     : std_logic_vector (3 downto 0) := "0011"; --Valor default para que lea el PC al principio
signal sig_loadBP     : std_logic;
signal sig_selBP      : std_logic;
signal sig_selPC      : std_logic;


signal sig_stalling      : std_logic := '0';

----

-- Fin de la declaraciÃ³n de los seÃ±ales.

begin


clk_switch <= clk and sw(15);
clock_selector(0) <= sw(0);
clock_selector(1) <= sw(1);

--led <= sw;

-- Inicio de declaraciÃ³n de comportadmientos.

--dis_a(0) <= btn(0); --boton central
--dis_b(0) <= btn(1);
--dis_c(0) <= btn(2);
--dis_d(0) <= btn(3);


 --Mostrar los bits de los registros en los displays!

dis_b(0) <= reg_a_out(0);
dis_b(1) <= reg_a_out(1);
dis_b(2) <= reg_a_out(2);
dis_b(3) <= reg_a_out(3);
dis_a(0) <= reg_a_out(4);
dis_a(1) <= reg_a_out(5);
dis_a(2) <= reg_a_out(6);
dis_a(3) <= reg_a_out(7);

dis_d(0) <= reg_b_out(0);
dis_d(1) <= reg_b_out(1);
dis_d(2) <= reg_b_out(2);
dis_d(3) <= reg_b_out(3);
dis_c(0) <= reg_b_out(4);
dis_c(1) <= reg_b_out(5);
dis_c(2) <= reg_b_out(6);
dis_c(3) <= reg_b_out(7);


--dis_d(0) <= pc_out(0);
--dis_d(1) <= pc_out(1);
--dis_d(2) <= pc_out(2);
--dis_d(3) <= pc_out(3);
--dis_c(0) <= pc_out(4);
--dis_c(1) <= pc_out(5);
--dis_c(2) <= pc_out(6);
--dis_c(3) <= pc_out(7);


led(0) <= cu_opcode_out(0);
led(1) <= cu_opcode_out(1);
led(2) <= cu_opcode_out(2);
led(3) <= cu_opcode_out(3);
led(4) <= cu_opcode_out(4);
led(5) <= cu_opcode_out(5);
led(6) <= cu_opcode_out(6);
led(7) <= cu_opcode_out(7);
led(8) <= cu_opcode_out(8);
led(9) <= cu_opcode_out(9);
led(10) <= cu_opcode_out(10);
led(11) <= cu_opcode_out(11);

--led(0) <= pc_out(0);
--led(1) <= pc_out(1);
--led(2) <= pc_out(2);
--led(3) <= pc_out(3);
--led(4) <= pc_out(4);
--led(5) <= pc_out(5);
--led(6) <= pc_out(6);
--led(7) <= pc_out(7);


led(12) <= sig_selAdd(0);
led(13) <= sig_selAdd(1);
led(14) <= sig_selAdd(2);
led(15) <= sig_selAdd(3);


--dis_a(0) <= clock;

-- Inicio de declaraciÃ³n de instancias.

inst_Clock_Divider: Clock_Divider port map( -- No Tocar - Intancia de Clock_Divider.
    clk         => clk_switch,  -- No Tocar - Entrada del clock completo (100Mhz).  -- ATENCION CAMBIAR A CLK ANTES DE ENTREGAR!
    speed       => clock_selector, -- Selector de velocidad: "00" full, "01" fast, "10" normal y "11" slow.  -- DEAFAULT 01
    clock       => clock -- No Tocar - Salida del clock reducido: 25Mhz, 8hz, 2hz y 0.5hz.
    );

inst_Display_Controller: Display_Controller port map( -- No Tocar - Intancia de Display_Controller.
    dis_a       => dis_a,-- No Tocar - Entrada de seÃ±ales para el display A.
    dis_b       => dis_b,-- No Tocar - Entrada de seÃ±ales para el display B.
    dis_c       => dis_c,-- No Tocar - Entrada de seÃ±ales para el display C.
    dis_d       => dis_d,-- No Tocar - Entrada de seÃ±ales para el display D.
    clk         => clk_switch,  -- No Tocar - Entrada del clock completo (100Mhz). -- ATENCION CAMBIAR A CLK ANTES DE ENTREGAR!   
    seg         => seg,  -- No Tocar - Salida de las seÃ±ales de segmentos.
    an          => an    -- No Tocar - Salida del selector de diplay.
	);

inst_Debouncer: Debouncer port map( -- No Tocar - Intancia de Debouncer.
    clk         => clk_switch,   -- No Tocar - Entrada del clock completo (100Mhz).  -- ATENCION CAMBIAR A CLK ANTES DE ENTREGAR!
    datain      => btn,   -- No Tocar - Entrada del botones con rebote.
    dataout     => d_btn  -- No Tocar - Salida de botones con antirrebote.
    );

-- 

inst_Reg_A: Reg port map (
        clock      => clock,
        load       => sig_enableA,
        up         => '0', -- Desabilitado, suma implementada de otra forma
        down       => '0', -- Desabilitado, resta implementada de otra forma
        datain     => alu_out,
        dataout    => reg_a_out
        );
        
 inst_Reg_B: Reg port map (
        clock      => clock,
        load       => sig_enableB,
        up         => '0', -- Desabilitado, suma implementada de otra forma
        down       => '0', -- Desabilitado, resta implementada de otra forma
        datain     => alu_out,
        dataout    => reg_b_out
        );
        
 inst_SP: SP port map (
        clock    => clock,
        load     => sig_LSP,                       
        up       => sig_incSP,
        down     => sig_decSP,
        datain   => alu_out,
        dataout  => sp_out
        );
        
 
  -- 
        
inst_Mux_PC: MuxSingle port map (
   in0      =>  cu_lit_out,
   in1      =>  memory_out,
   sel      =>  sig_selPC,
   out_mux  =>  mux_pc_out
   );

inst_PC: PC port map(
    clock       => clock,
    load        => sig_loadPC,
    stalling    => sig_stalling,
    datain      => mux_pc_out,
    dataout     => pc_out
    );
    
inst_Mux_BP: MuxSingle port map (
   in0      =>  memory_out,
   in1      =>  sp_out,
   sel      =>  sig_selBP,
   out_mux  =>  mux_bp_out
   );

inst_BP: BP port map (
   clock    => clock,
   load     => sig_loadBP,
   datain   =>  mux_bp_out,
   dataout  => bp_out
   );
   
   
inst_Reg_CU: RegisterCU port map (
   clock    => clock,
   datain   => memory_out,
   dataout  => cu_opcode_out,
   lit      => cu_lit_out,
   stalling => sig_stalling
   -- OJO! NO SE ESTÁ CONECTANDO SELADDSEC
   );
   
   
inst_AdderSub1: AdderSubstractor port map (
    a    => "000000000001",
    b    => pc_out,
    e    => '0',
    s    => adder_sub_out1
    );
   
inst_AdderSub2: AdderSubstractor port map (
    a    => bp_out,
    b    => cu_lit_out,
    e    => sig_sub,
    s    => adder_sub_out2
    );
    
 inst_MuxAddress: MuxAddress port map (
    in_add_sub => adder_sub_out2,
    in_sp => sp_out,
    in_lit => cu_lit_out,
    in_pc  => pc_out,
    in_reg => reg_b_out,
    sel => sig_selAdd,
    out_mem => mux_add_out
    );
    
  inst_MuxData: MuxDouble port map (
    in0  => alu_out,
    in1  => pc_out,
    in2  => adder_sub_out1,
    in3  => bp_out,
    sel  => sig_selDin,
    out_mux  => mux_data_out   
    );
    
    
  inst_MuxA: MuxDouble port map (
    in0  => reg_a_out,
    in1  => (others => '0'),
    in2  => sp_out,
    in3  => "000000000001",
    sel  => sig_selA,
    out_mux  => mux_a_out
    );
    
    
  inst_MuxB: MuxDouble port map (
    in0  => cu_lit_out,
    in1  => memory_out,
    in2  => reg_b_out,
    in3  => (others => '0'),
    sel  => sig_selB,
    out_mux  => mux_b_out   
    );
    
  inst_MainMemory: MainMemory port map (
    clock    =>  clock,
    write    =>  sig_W,
    address  =>  mux_add_out,
    datain   =>  mux_data_out,
    dataout  =>  memory_out
    );
    
    
  inst_ALU: ALU port map (
    a        =>  mux_a_out,
    b        =>  mux_b_out,
    sop      =>  sig_selALU,
    c        =>  alu_c_out,
    z        =>  alu_z_out,               
    n        =>  alu_n_out,
    o        =>  alu_o_out,
    result   =>  alu_out
    );
    
  inst_Status: Status port map (
    clock    => clock,
    c        => alu_c_out,
    z        => alu_z_out,
    n        => alu_n_out,
    o        => alu_o_out,
    dataout  => status_out
    );
    
    
  inst_ControlUnit: ControlUnit port map (
        in_op      => cu_opcode_out,
        in_status  => status_out,
        enableA    => sig_enableA,
        enableB    => sig_enableB,
        selA       => sig_selA,
        selB       => sig_selB,
        loadPC     => sig_loadPC,
        selALU     => sig_selALU,
        W          => sig_W,
        incSP      => sig_incSP,
        decSP      => sig_decSP,
        LSP        => sig_LSP,
        sub        => sig_sub,
        selDin     => sig_selDin,
        selAdd     => sig_selAdd,
        loadBP     => sig_loadBP,
        selBP      => sig_selBP,
        selPC      => sig_selPC
        );

-- Fin de declaraciÃ³n de instancias.

-- Fin de declaraciÃ³n de comportamientos.
  
end Behavioral;
